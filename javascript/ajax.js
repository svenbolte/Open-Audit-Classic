/**********************************************************************************************************
Module Comments:
	
	[Nick Brown]	20/08/2008
	The code in this module provides AJAX-style functionality through two objects:
	HttpRequestor - Uses XMLHttpRequest object to retrieve HTML content from URL
	XmlRequestor - Uses XmlDom object to retrive XML content from URL
	
**********************************************************************************************************/

// SERVERXMLHTTP options
var SXH_OPTION_URL_CODEPAGE = 0;
var SXH_OPTION_ESCAPE_PERCENT_IN_URL = 1;
var SXH_OPTION_IGNORE_SERVER_SSL_CERT_ERROR_FLAGS = 2;
var SXH_OPTION_SELECT_CLIENT_SSL_CERT = 3;

//SXH_SERVER_CERT_OPTION
var SXH_SERVER_CERT_IGNORE_UNKNOWN_CA = 256;
var SXH_SERVER_CERT_IGNORE_WRONG_USAGE = 512;
var SXH_SERVER_CERT_IGNORE_CERT_CN_INVALID = 4096;
var SXH_SERVER_CERT_IGNORE_CERT_DATE_INVALID = 8192;
var SXH_SERVER_CERT_IGNORE_ALL_SERVER_ERRORS = 13056;

// AJAX timeout 5000 milliseconds
var AJAX_TIMEOUT = 5000;

/**********************************************************************************************************
Function Name:
	HttpRequestor
Description:
	Class wrapper for the XMLHttpRequest object. Send function can be invoked. Returned HTML is then written back to the 
	HTML DOM object whose ID matches ID argument.
Arguments:
	ID		[IN] [string]	ID of DOM object that will receive the returned HTML string
	Async	[IN] [string]	Async flag - not currently used.
Returns:	None
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function HttpRequestor(ID, Async)
{
  this.ElementID = ID;
	this.XmlHttpObject = undefined;
	if (typeof Async == 'undefined') Async = 0;
	this.Async = Async;

	/*******************************************************************************************************
	Function Name: send
	Description:	Invokes the send method of XmlHttpObject using the supplied URL.
	Arguments:
		ID		[IN] [string]	URL to request
	Returns:	None
	Change Log:
		20/08/2008			New function	[Nick Brown]
	*******************************************************************************************************/
	this.send = function(sURL)
	{
	  this.XmlHttpObject = GetXmlHttpObject();
	  var _this = this;
		this.XmlHttpObject.onreadystatechange = function(){_this.receive()};
		this.XmlHttpObject.open("GET",sURL,true);
		this.XmlHttpObject.send(null);
	}

	/******************************************************************************************************
	Function Name:	receive
	Description:
		Called when data is returned from the requested URL. The HTML string is written back to the HTML DOM object 
		defined by the ElementID property.
	Arguments:	None
	Returns:	None
	Change Log:
		20/08/2008			New function	[Nick Brown]
	******************************************************************************************************/
	this.receive = function()
	{
		if (this.Async == 1)
		{	
			document.getElementById(this.ElementID).innerHTML = this.XmlHttpObject.responseText;
			return;
		}
		if (this.XmlHttpObject.readyState == 4 || this.XmlHttpObject.readyState == "complete")
		{
		  document.getElementById(this.ElementID).innerHTML = this.XmlHttpObject.responseText;
		}
	}
}

/**********************************************************************************************************
Function Name:
	GetXmlHttpObject
Description:
	Create XMLHttprequest object - browser specific. Mozilla browsers have XMLHttpRequest object built in. IE uses activex 
	objects of which there are many versions. 
Arguments:	None
Returns:	XMLHttprequest object
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function GetXmlHttpObject()
{
	// Firefox, Mozilla, Opera, etc.
	if (window.XMLHttpRequest) return new XMLHttpRequest();
	else if (window.ActiveXObject) 
	// IE only
	{
		var progids = ["MSXML2.ServerXMLHTTP","Msxml2.XMLHTTP.5.0", "Msxml2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"];
    var obj;
		for(var i = 0; i < progids.length; i++)
    {
      try
      {
				obj = new ActiveXObject(progids[i]);
				//if(i == 0) {obj.setOption(2, SXH_SERVER_CERT_IGNORE_ALL_SERVER_ERRORS);}
        return obj;
			}
	    catch (e){}
		}
	}
}

/**********************************************************************************************************
Function Name:
	XmlRequestor
Description:
	Class wrapper for the XML DOM object. Returns an XML DOM object  & XML string in response to supplied URL
Arguments:
	url		[IN] [string]	Optional url from which XML string is returned
Returns:	None
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function XmlRequestor(url)
{
	this.XmlString = '';
	this.ParseError = '';
	this.XmlDomObject = GetXmlDomObject();

	this.IE = (window.ActiveXObject) ? true : false;
	if (!this.IE)
	{
		this.XmlSerial = new XMLSerializer();
		this.XmlParser = new DOMParser();
	}

	/******************************************************************************************************
	Function Name:	CheckForParseError
	Description:
		Checks this.XmlDomObject for parse errors and poplulates this.ParseError with error description
	Arguments:	None
	Returns:	None
	Change Log:
		08/01/2009			New function	[Nick Brown]
	******************************************************************************************************/
	this.CheckForParseError = function ()
	{
		if (this.IE)
		{
			this.ParseError = (this.XmlDomObject.parseError.errorCode != 0) ? this.XmlDomObject.parseError.reason : '';
		}
		else
		{
			this.ParseError = (this.XmlDomObject.documentElement.nodeName == "parsererror") ? this.XmlDomObject.documentElement.childNodes[0].nodeValue : '' ;
		}
	}
	
	/******************************************************************************************************
	Function Name:	SerializeXmlNode
	Description:
		Returns a serialized XML string representation of an XML node
	Arguments:
		url	[IN] [node object]		XML DOM node
	Returns:	[string]  XML string
	Change Log:
		10/09/2008			New function	[Nick Brown]
	******************************************************************************************************/
	this.SerializeXmlNode = function (node)
	{
		XmlString = (this.IE == true) ? node.xml : this.XmlSerial.serializeToString(node);
		return XmlString;
	}

	/******************************************************************************************************
	Function Name:	GetXMLDocFromString
	Description:
		Loads an XML file from supplied string into XML DOM (and stores XML string in this.XmlString)
	Arguments:
		url	[IN] [string]	url from which XML DOM is returned
	Returns:	XML DOM object
	Change Log:
		10/09/2008			New function	[Nick Brown]
	******************************************************************************************************/
	this.GetXMLDocFromString = function(XmlString)
	{
		this.XmlString = XmlString;
		if (this.IE) 
		{
			this.XmlDomObject.loadXML(XmlString);
		}
		else 
    {
			this.XmlDomObject = this.XmlParser.parseFromString(XmlString,"text/xml");
		}
    return(this.XmlDomObject);
	}
	
	/******************************************************************************************************
	Function Name:	GetNode
	Description:
		Returns the *first* XML node whose tag matches the supplied Tag name
	Arguments:
		TagName	[IN] [string]	Name of node tag whose value is to be returned
	Returns:	
		[node object]	The node within the XML doc
	Change Log:
		10/09/2008			New function	[Nick Brown]
	******************************************************************************************************/
	this.GetNode = function(TagName)
	{
		var nodes = this.XmlDomObject.documentElement.getElementsByTagName(TagName);
		if(nodes.length == 0){return "";}
		return nodes[0];
	}

	/******************************************************************************************************
	Function Name:	GetValue
	Description:
		Returns the value from the *first* XML node whose tag matches the supplied Tag name
	Arguments:
		TagName	[IN] [string]	Name of node tag whose value is to be returned
	Returns:	
		[String]	The value contained within the XML tag
	Change Log:
		20/08/2008			New function	[Nick Brown]
		14/11/2009			Return empty if there is no child	[Chad Sikorra]
	******************************************************************************************************/
	this.GetValue = function(TagName)
	{
		if(this.GetNode(TagName).firstChild==null){return "";}
		return this.GetNode(TagName).firstChild.nodeValue;
	}

	/******************************************************************************************************
	Function Name:	GetXMLDocFromUrl
	Description:
		Loads an XML file from supplied URL into XML DOM and stores XML string in this.XmlString
	Arguments:
		url	[IN] [string]	url from which XML DOM is returned
	Returns:	XML DOM object
	Change Log:
		20/08/2008			New function	[Nick Brown]
		07/01/2009			Added ParseError functionality [Nick Brown]
		08/01/2009			Replaced XmlDomObject .Load() with GetXMLResponseFromUrl() [Nick Brown]
									Moved parse error checking to new function CheckForParseError() [Nick Brown]
	******************************************************************************************************/
	this.GetXMLDocFromUrl = function(url)
	{
		var xml = GetXMLResponseFromUrl(url);
		this.GetXMLDocFromString(xml);
	}
	
	// If constructor was passed a URL, invoke GetXMLDocFromUrl to load XML file immediately
	if (url != undefined) {this.GetXMLDocFromUrl(url);}
}

/**********************************************************************************************************
Function Name:
	GetXmlDomObject
Description:
	Create XML DOM object - browser specific. Mozilla browsers have DOMParser object built in. IE uses activex 
	objects of which there are many versions. 
Arguments:	None
Returns:	XMLHttprequest object
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function GetXmlDomObject()
{
  var oXmlDoc = undefined;
	if (window.ActiveXObject)
	{
		// IE Only
		var progids = ["Msxml2.DOMDocument.6.0", "Msxml2.DOMDocument.4.0", "Msxml2.DOMDocument.3.0", "Msxml2.DOMDocument", "Msxml.DOMDocument"];
		for(var i = 0; i < progids.length; i++)
    {
      try
      {
				oXmlDoc = new ActiveXObject(progids[i]);
				try
				{
					oXmlDoc.setProperty("SelectionLanguage", "XPath");
					//oXmlDoc.async = false;
				}
				catch (e){}
				return oXmlDoc;
			}
	    catch (e){}
		}
	}
	else
	// Firefox, Mozilla, Opera, etc.
	{
		oXmlDoc = document.implementation.createDocument("","",null);
	}

	oXmlDoc.async = false;
	return oXmlDoc;
}

/**********************************************************************************
	Used by XmlRequestor.GetXMLDocFromUrl()
**********************************************************************************/
function GetXMLResponseFromUrl(url)
{
  var XmlHttpObject = GetXmlHttpObject();
	XmlHttpObject.open("GET", url, false);
	XmlHttpObject.send(null);
	var start = new Date();
	while(XmlHttpObject.readyState != 4) {if(IsTimedOut(start)) break;}
	return XmlHttpObject.responseText;          
}

/**********************************************************************************************************
Function Name:
	IsTimedOut
Description: Checks if AJAX request is timed-out
Arguments:
		start	[IN] [Date]	time that AJAX request was initiated
Returns:	True/False
Change Log:
	12/01/2009			New function	[Nick Brown]
**********************************************************************************************************/
function IsTimedOut(start)
{
	if((new Date().getTime() - start.getTime()) > AJAX_TIMEOUT) {return true;}
	else {return false;}
}
