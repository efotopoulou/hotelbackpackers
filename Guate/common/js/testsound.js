var sound2Embed=null;
function sound2Play() {
  if ( !sound2Embed ) {
    sound2Embed = document.createElement("embed");
    sound2Embed.setAttribute("src", "/common/sound/tada.wav");
    sound2Embed.setAttribute("hidden", true);
  } else sound2Stop();
  sound2Embed.removed = false;
  document.body.appendChild(sound2Embed);
}
function sound2Stop() {
  if ( sound2Embed && !sound2Embed.removed ) {
    document.body.removeChild(sound2Embed);
    sound2Embed.removed = true;
  }
}
