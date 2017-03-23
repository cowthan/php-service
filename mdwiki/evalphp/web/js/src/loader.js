var cSpeed = 9;
var cWidth = 20;
var cHeight = 20;
var cTotalFrames = 12;
var cFrameWidth = 20;
var cImageSrc = '/images/sprites.png';

var cImageTimeout = false;

function startAnimation() {

    document.getElementById('loader').innerHTML = '<canvas id="canvas" width="' + cWidth + '" height="' + cHeight + '"><p>Your browser does not support the canvas element.</p></canvas>';

    FPS = Math.round(100 / cSpeed);
    SECONDS_BETWEEN_FRAMES = 1 / FPS;
    g_GameObjectManager = null;
    g_run = genImage;

    g_run.width = cTotalFrames * cFrameWidth;
    genImage.onload = function () {
        cImageTimeout = setTimeout(fun, 0);
    };
    initCanvas();
}


function imageLoader(s, fun)//Pre-loads the sprites image
{
    clearTimeout(cImageTimeout);
    cImageTimeout = 0;
    genImage = new Image();
    genImage.onload = function () {
        cImageTimeout = setTimeout(fun, 0);
    };
    genImage.onerror = new Function('alert(\'Could not load the image\')');
    genImage.src = s;
}

//The following code starts the animation
new imageLoader(cImageSrc, 'startAnimation()');