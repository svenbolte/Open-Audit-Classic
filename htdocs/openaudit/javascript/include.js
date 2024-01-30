/**********************************************************************************************************
Module:	include.js

Description:
	This module is included by "include.php". Provides functions for the correct operation of the menu.
	
**********************************************************************************************************/

/**********************************************************************************************************
Function Name:
	switchUl
Description:
	Toggles element display between "none" and "block" to hide/show element
Arguments:	id of html element to switch
Returns:	None
**********************************************************************************************************/
function switchUl(id)
{
	if(document.getElementById)
	{
		a = document.getElementById(id);
		a.style.display = (a.style.display!="none") ? "none" : "block";
	}
}
