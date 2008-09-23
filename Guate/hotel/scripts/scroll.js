function ScrollIni () {
addScrollSynchronization(document.getElementById("calend_top"), document.getElementById("calend_mid"), "horizontal");
addScrollSynchronization(document.getElementById("calend_left"), document.getElementById("calend_mid"), "vertical");
//addScrollSynchronization(document.getElementById("div4"), document.getElementById("div1"), "both");
};

// This is a function that returns a function that is used
// in the event listener
function getOnScrollFunction(oElement) {
return function (event) {
if (!event) var event = window.event;
if (oElement._scrollSyncDirection == "horizontal" || oElement._scrollSyncDirection == "both"){
	element = event.target || event.srcElement;
	oElement.scrollLeft = element.scrollLeft;
	}
if (oElement._scrollSyncDirection == "vertical" || oElement._scrollSyncDirection == "both"){
	element = event.target || event.srcElement;
	oElement.scrollTop = element.scrollTop;
	}
};

}
// This function adds scroll syncronization for the fromElement to the toElement
// this means that the fromElement will be updated when the toElement is scrolled
function addScrollSynchronization(fromElement, toElement, direction) {
removeScrollSynchronization(fromElement);
fromElement._syncScroll = getOnScrollFunction(fromElement);
fromElement._scrollSyncDirection = direction;
fromElement._syncTo = toElement;
if (window.addEventListener)
	toElement.addEventListener("scroll", fromElement._syncScroll, true);
else
	toElement.attachEvent("onscroll", fromElement._syncScroll);
}

// removes the scroll synchronization for an element
function removeScrollSynchronization(fromElement) {
if (fromElement._syncTo != null)
fromElement._syncTo.detachEvent("onscroll", fromElement._syncScroll);

fromElement._syncTo = null;
fromElement._syncScroll = null;
fromElement._scrollSyncDirection = null;
}