/**********************************************************************************************************
	Pop-it menu- © Dynamic Drive (www.dynamicdrive.com)
	This notice MUST stay intact for legal use
	Visit http://www.dynamicdrive.com/ for full source code
	
	20/08/2008 - Modified for use by Open Audit [Nick Brown]
**********************************************************************************************************/
var oMenu;
var bIe = document.all && !window.opera

document.onclick = HideMenu;

/**********************************************************************************************************
Function Name:
	getEventTarget
Description:
	Get and identify the source of the event object
Arguments:
	x	[object]	DHTML object trigerring the event
Returns:	
	Javascript Event object
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function getEventTarget(x)
{ 
	x = x || window.event;
	return x.target || x.srcElement;
} 

/**********************************************************************************************************
Function Name:
	ShowMenu
Description:
	Display the menu (Div). Calculate position in relation to event source position.
Arguments:
	e					[object]	Event object
	MenuHTML		[String]	HTML string defining the menu div content
Returns:	False
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function ShowMenu(e, MenuHTML)
{
	if (!document.all && !document.getElementById) return;
	ClearHideMenu(e);
	oMenu = document.getElementById("npb_popupmenu_div");
	oMenu.innerHTML = MenuHTML;
	oMenu.style.width = "120px";
	oMenu.contentwidth = oMenu.offsetWidth;
	oMenu.contentheigh = oMenu.offsetHeight;
	eventX = e.clientX;
	eventY = e.clientY;
	
	//Find out how close the mouse is to the corner of the window
	var RightEdge = bIe ? GetIEDocBody().clientWidth - eventX : window.innerWidth-eventX
	var BottomEdge = bIe ? GetIEDocBody().clientHeight - eventY : window.innerHeight-eventY
	
	//if the horizontal distance isn't enough to accomodate the width of the context menu
	if (RightEdge < oMenu.contentwidth) 
	{oMenu.style.left = bIe ? GetIEDocBody().scrollLeft + eventX - oMenu.contentwidth + "px" : window.pageXOffset + eventX - oMenu.contentwidth + "px";}
	//move the horizontal position of the menu to the left by it's width
	else
	//position the horizontal position of the menu where the mouse was clicked
	{oMenu.style.left = bIe ? GetIEDocBody().scrollLeft+eventX + "px" : window.pageXOffset + eventX + "px"}
	
	//same concept with the vertical position
	if (BottomEdge < oMenu.contentheight)
	{oMenu.style.top = bIe ? GetIEDocBody().scrollTop + eventY - oMenu.contentheight + "px" : window.pageYOffset + eventY - oMenu.contentheight + "px";}
	else {oMenu.style.top = bIe ? GetIEDocBody().scrollTop + event.clientY + "px" : window.pageYOffset + eventY + "px";}

	// Added by Nick Brown - kludge to pass the id of the link to the npb_popupmenu_div via a hidden DIV on the menu
	document.getElementById("popupmenu_id").innerHTML = getEventTarget(e).id;
	
	oMenu.style.visibility = "visible";
	return false;
}

/**********************************************************************************************************
Function Name:
	HideMenu
Description:
	Hides the menu div
Arguments: None
Returns:	None
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function HideMenu()
{if (window.oMenu) oMenu.style.visibility = "hidden";}

/**********************************************************************************************************
Function Name:
	DelayHideMenu
Description:
	Schedule menu div to be hidden after 300 ms
Arguments: None
Returns:	None
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function DelayHideMenu()
{DelayHide = setTimeout("HideMenu()",300);}

/**********************************************************************************************************
Function Name:
	ClearHideMenu
Description:
	If menu div is scheduled to be hidden, cancel the schedule
Arguments: None
Returns:	None
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function ClearHideMenu(e)
{if (window.DelayHide) clearTimeout(DelayHide);}

/**********************************************************************************************************
Function Name:
	DynamicHide
Description:
Arguments:
	e	[object]	Event object
Returns:	None
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function DynamicHide(e)
{if (e.currentTarget != e.relatedTarget && !Contains(e.currentTarget, e.relatedTarget)) HideMenu();}

/**********************************************************************************************************
Function Name:
	Contains
Description:
	Determines if 1 element in contained in another- by Brainjar.com
Arguments:
	a	[object]	DOM node
	b	[object]	DOM node
Returns:	True if a "contains" b, else returns false
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function Contains(a, b)
{
	while (b.parentNode)
	{if ((b = b.parentNode) == a) return true;}
	return false;
}
/**********************************************************************************************************
Function Name:
	GetIEDocBody
Description:
Arguments: None
Returns:	[object] 	DHTML "Body" object
Change Log:
	20/08/2008			New function	[Nick Brown]
**********************************************************************************************************/
function GetIEDocBody()
{
	return (document.compatMode && document.compatMode.indexOf("CSS")!=-1)? document.documentElement : document.body
}
