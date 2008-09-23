function initPage()
{
	var nav = document.getElementById("navigation");
	if (nav)
	{
		var nodes = nav.getElementsByTagName("li");
		for (var i = 0; i < nodes.length; i++)
		{
			if (nodes[i].parentNode.id == "navigation")
			{
				nodes[i].onmouseover = function () 
				{
					this.className += " hover";
				}
				nodes[i].onmouseout = function ()
				{
					this.className = this.className.replace(" hover", "");
				}
			}
		}
	}
}
if (window.attachEvent && !window.opera)
	window.attachEvent("onload", initPage);
