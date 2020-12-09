/**********************************************************************************************************
Module:	include.js

Description:
	This module is included by "include.php". Provides functions for the correct operation of the menu.

Change Control:
	
	[Nick Brown]	29/04/2009
	Moved code from "admin_config.php".
	
**********************************************************************************************************/

/**********************************************************************************************************
Function Name:
	IEHoverPseudo
Description:
	Simulates the "Hover" CSS psuedo selector in IE by changing element class in the "onmouseover" and "onmouseout" events
Arguments:	None
Returns:	None
Change Log:
	29/04/2009			Function moved from include.php	[Nick Brown]
**********************************************************************************************************/
function IEHoverPseudo()
{
	var navItems = document.getElementById("primary-nav").getElementsByTagName("li");
	for (var i=0; i<navItems.length; i++)
	{
		if(navItems[i].className == "menuparent")
		{
			navItems[i].onmouseover=function() {this.className = "menuparent over";}
			navItems[i].onmouseout=function() {this.className = "menuparent";}
		}
	}
}

/**********************************************************************************************************
Function Name:
	switchUl
Description:
	Toggles element display between "none" and "block" to hide/show element
Arguments:	id of html element to switch
Returns:	None
Change Log:
	29/04/2009			Function moved from include.php	[Nick Brown]
**********************************************************************************************************/
function switchUl(id)
{
	if(document.getElementById)
	{
		a = document.getElementById(id);
		a.style.display = (a.style.display!="none") ? "none" : "block";
	}
}
