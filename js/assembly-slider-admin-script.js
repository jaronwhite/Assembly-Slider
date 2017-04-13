// window.addEventListener('load', function () {

function twoDigits(d) {
    if (0 <= d && d < 10) return "0" + d.toString();
    if (-10 < d && d < 0) return "-0" + (-1 * d).toString();
    return d.toString();
}

Date.prototype.toMysqlFormat = function () {
    return this.getFullYear() + "-" + twoDigits(1 + this.getMonth()) + "-" + twoDigits(this.getDate()) + " " + twoDigits(this.getHours()) + ":" + twoDigits(this.getMinutes()) + ":" + twoDigits(this.getSeconds());
};

var editSlideIndex;
var editLayerIndex;

// var rgmSlider = {
//     slider_id: 'slider' + Date.now(),
//     slider_name: '',
//     date_created: new Date().toMysqlFormat(),
//     size: 'full',
//     speed: '6000',
//     b_control: true,
//     lr_control: false,
//     slides: []
// };


/* ************************ *\
 ** ****[[ELEMENT VARS]]**** **
 \* ************************ */

var outerWrap = document.getElementById('outer-wrap'); //container
var innerWrap = document.getElementById('inner-wrap'); //container
var sliderSelectionForm = document.getElementById('slider-selection-form'); //form
var sliderSelector = document.getElementById('slider-selector'); //select box
var sliderGo = document.getElementById('slider-go'); //input submit
var assemblySliderForm = document.getElementById('assembly-slider-form'); //form
var sliderPane = document.getElementById('slider-pane'); //container
var sliderName = document.getElementById('slider-name'); //input text
var sliderCode = document.getElementById('slider-code'); //input text
var sliderOptions = document.getElementById('slider-options'); //container
var sliderSpeed = document.getElementById('slider-speed'); //select box
var bControl = document.getElementById('b-control'); //input checkbox
var lrControl = document.getElementById('lr-control'); //input checkbox
var slidesPane = document.getElementById('slides-pane'); //container
var slidesOuterWrap = document.getElementById('slides-outer-wrap'); //container
var slidesInnerWrap = document.getElementById('slides-inner-wrap'); //container
var addSlide = document.getElementById('add-slide'); //element
var slides = document.getElementsByClassName('slide'); //element array
var slideEditor = document.getElementById('slide-editor'); //container
var toolPane = document.getElementById('tool-pane'); //container
var slideBgTool = document.getElementById('slide-bg-tool'); //container
var bgImgSelect = document.getElementById('bg-img-select'); //input button
var mediaSelect = document.getElementsByClassName('media-select'); //input button array
var bgImgURL = document.getElementById('bg-img-url'); //input text
var layerAlignTool = document.getElementById('layer-align-tool'); //container
var textAlignTool = document.getElementById('text-align-tool'); //container
var devicePreviewTool = document.getElementById('device-preview-tool'); //container
//^above contains [mobile, tablet, desktop, rotate-device]^
var mobile = document.getElementById('mobile'); //element
var slideMirror = document.getElementById('slide-mirror'); //container
var deviceDetails = document.getElementById('device-details'); //p element
var deviceDesc = document.getElementById('device-desc'); //p element
var deviceOrientation = document.getElementById('device-orientation'); //p element
var deviceEmulator = document.getElementById('device-emulator'); //container
var layers = document.getElementsByClassName('layer'); //input button array
var editable = document.getElementsByClassName('editable'); //element array
var layerContentPane = document.getElementById('layer-content-pane'); //container
var layerType = document.getElementById('layer-type'); //select box
var contentArea = document.getElementById('content-area'); //text area box
var layerImgSelect = document.getElementById('layer-img-select'); //input button
var staticOptionsWrap = document.getElementById('static-options-wrap'); //container
var stationaryLayer = document.getElementById('stationary-layer'); //input checkbox
var staticLayer = document.getElementById('static-layer'); //input checkbox
var layerContentsSaveWrap = document.getElementById('layer-contents-save-wrap'); //container
var saveContent = document.getElementById('save-content'); //input button
var closeContent = document.getElementById('close-content'); //input button
var saveBtns = document.getElementsByClassName('save-btn'); //input button array
var layerPane = document.getElementById('layer-pane'); //container
var layerOptionsWrap = document.getElementById('layer-options'); //container 
var layerOptions = document.getElementsByClassName('layer-option'); //div element array
var addLayer = document.getElementById('add-layer'); //input button

// var savePane = document.getElementById('save-pane'); //container
// var saveQuit = document.getElementById('save-quit'); //input submit
// var saveCont = document.getElementById('save-cont'); //input submit
// var cancelBuild = document.getElementById('cancel-build'); //input submit
// var sliderCommit = document.getElementById('slider-commit'); //container
// var commitStmt = document.getElementById('commit-stmt'); //h1 element
// var saveConfirm = document.getElementById('save-confirm'); //input submit
// var saveDecline = document.getElementById('save-decline'); //input submit

var saveSlider = document.getElementById('save-slider'); //input submit


/* ************************ *\
 ** *****[[USER VARS]]***** **
 \* ************************ */

var moveClick;
var newLayer = false;
var layerSide;
var SIDES = ['top', 'right', 'bottom', 'left'];
var mirWidth = deviceEmulator.offsetWidth;
var mirHeight = deviceEmulator.offsetHeight;


/* ************************ *\
 ** ***[[Slider Objects]]*** **
 \* ************************ */

function Slide(slide_id, date_created, bg, slide_order, layers, slider_id) {
    this.slide_id = slide_id;
    this.date_created = date_created;
    this.bg = bg; //css i.e. url(...) or rgb(2,2,5)
    this.slide_order = slide_order;
    this.transition = 'fade';
    this.layers = layers;
    this.slider_id = slider_id;
}

function Layer(layer_id, date_created, type, content, inner_classList, stationary, static, css, slide_id) {
    this.layer_id = layer_id;
    this.date_created = date_created;
    this.type = type;
    this.content = content;
    this.inner_classList = inner_classList;
    this.stationary = stationary;
    this.static = static;
    this.css = css;
    this.slide_id = slide_id;
}

function CSS(style_id, date_created, media_query, top, left, px_width, perc_width, max_width, min_width, px_height, perc_height, max_height,
             min_height, transform, border, border_radius, background, opacity, text_align, font_family, font_weight,
             font_size, font_style, color, z_index, layer_id) {
    this.style_id = style_id;
    this.date_created = date_created;
    this.media_query = media_query;
    this.top = top;
    this.left = left;
    this.px_width = px_width;
    this.perc_width = perc_width;
    this.max_width = max_width;
    this.min_width = min_width;
    this.px_height = px_height;
    this.perc_height = perc_height;
    this.max_height = max_height;
    this.min_height = min_height;
    this.transform = transform;
    this.border = border;
    this.border_radius = border_radius;
    this.background = background;
    this.opacity = opacity;
    this.text_align = text_align;
    this.font_family = font_family;
    this.font_weight = font_weight;
    this.font_size = font_size;
    this.font_style = font_style;
    this.color = color;
    this.z_index = z_index;
    this.layer_id = layer_id;
}

function applyStyles(obj, el, sttc) {
    for (var key in obj) {
        if (obj.hasOwnProperty(key) && !isEmpty(obj[key])) {
            var y = key.replace('_', '-');
            var pre = y.split('-')[0];
            var post = y.split('-')[1];

            if (pre === 'perc') {
                if (!sttc) {
                    el.style[post] = obj[key];
                }
            } else if (pre === 'px') {
                if (sttc) {
                    el.style[post] = obj[key];
                }
            } else {
                el.style[y] = obj[key];
            }
            // console.log(key + ': ' + obj[key]);
        }
    }
}

function toCSSString(OBJ) {
    var string = '';
    for (var key in OBJ) {
        if (OBJ.hasOwnProperty(key)) {
            if (!isEmpty(OBJ[key])) {
                string += key.replace('_', '-') + ': ' + OBJ[key] + ';\n';
            }
        }
    }
}


/* ******************** *\
 ** *****[[EVENTS]]***** **
 \* ******************** */

sliderName.addEventListener('keyup', generateShortCode);
sliderCode.addEventListener('click', highlightText);
sliderOptions.addEventListener('change', updateSliderOptions);
slidesInnerWrap.addEventListener('click', function (e) {
    if (e.target.classList.contains('slide')) {
        slideClick(e.target);
    }
});
addSlide.addEventListener('click', newSlideClick);
deviceEmulator.addEventListener('click', function (e) {
    if (e.target === this) {
        editLayerIndex = -1;
        if (editable.length > 0) {
            editable[0].classList.remove('editable');
        }
    }
});
addLayer.addEventListener('click', newLayerClick);
layerContentsSaveWrap.addEventListener('click', function (e) {
    if (e.target != this) {
        updateLayerContents(e.target.id);
    }
});
layerAlignTool.addEventListener('click', function (e) {
    if (e.target != this) {
        sizeCalc(e);
        alignLayer(e.target.id);
    }
});
textAlignTool.addEventListener('click', function (e) {
    if (e.target != this && rgmSlider.slides[editSlideIndex].layers[editLayerIndex].type === 'text') {
        asAlignText(e.target.id);
    }
});


// cancelBuild.addEventListener('click', verifySave);
// saveQuit.addEventListener('click', verifySave);
// saveConfirm.addEventListener('click', function () {
//     //submit date to db
//     //Will add as AJAX request later, for now PHP that stuff...
// });
// saveDecline.addEventListener('click', function (e) {
//     //destroy new slider objects? Maybe only do this on navigating away from page...
//     closeSlideEditor(e);
// });
/*********************************************************************************************
 * After AJAX build complete, include options to continue editing or quit and close the editor.
 *********************************************************************************************/
// saveQuit.addEventListener('click', closeSlideEditor);
// saveCont.addEventListener('click', function(){});


/* ***************** *\
 ** ***[FUNCTIONS]*** **
 \* ***************** */

/**
 * Initiate the generated code for copy/paste
 */
(function () {

    if (rgmSlider.slider_name != '') {
        sliderSelector.value = rgmSlider.slider_name;
        sliderName.value = sliderSelector.value;
        sliderSpeed.value = (rgmSlider.speed / 1000);
        if (rgmSlider.lr_control === 1) {
            lrControl.checked = true;
        }
        if (rgmSlider.b_control === 1) {
            bControl.checked = true;
        }
        generateShortCode();
    } else {
        sliderName.placeholder = "Type slider name here.";
    }
})();

/**
 * If rgmSlider is not empty, build the editor
 */
(function () {
    for (var i = 0; i < rgmSlider.slides.length; i++) {
        addSlideEl(i);
    }
})();

/**
 * Generate the shortcode for user copy/paste
 */
function generateShortCode() {
    var sliderNameVal = sliderName.value.trim();
    rgmSlider.slider_name = sliderNameVal;
    sliderCode.value = '[slider name="' + sliderNameVal + '"]';
}

/**
 * Selects all text within textBox
 */
function highlightText() {
    this.setSelectionRange(0, this.value.length);
}

/**
 * Update the options in the slider object's attributes
 * @param e -- the event
 */
function updateSliderOptions(e) {
    if (e.target.nodeName == 'INPUT') {
        if (e.target.id == 'lr-control') {
            rgmSlider.lr_control = e.target.checked;
        } else if (e.target.id == 'b-control') {
            rgmSlider.b_control = e.target.checked;
        }
    } else if (e.target.nodeName == 'SELECT') {
        rgmSlider.speed = e.target.value * 1000;
    }
}

function newSlideClick() {
    var slideCount = rgmSlider.slides.length;
    editSlideIndex = slideCount;
    rgmSlider.slides[slideCount] = new Slide('slide' + Date.now(), new Date().toMysqlFormat(), '', slideCount, [], rgmSlider.slider_id);

    //VISUAL FUNCTIONS
    openSlideEditor();
    mobile.click();
    addSlideEl(editSlideIndex);
    washMirror();
}

function slideClick(el) {
    openSlideEditor();
    mobile.click();
    editSlideIndex = nodeIndex(el);
    console.log(rgmSlider.slides[editSlideIndex].layers);
    reflectSlide();
}

/**
 * Clears the slide mirror of contents
 */
function washMirror() {
    bgImgURL.value = '';
    deviceEmulator.innerHTML = '';
    deviceEmulator.style.background = '';
}

/**
 *Adds content of slide being edited to mirror
 */
function reflectSlide() {
    washMirror();
    bgImgURL.value = textInParentheses(rgmSlider.slides[editSlideIndex].bg);
    deviceEmulator.style.background = rgmSlider.slides[editSlideIndex].bg;
    deviceEmulator.style.backgroundSize = 'cover';
    for (var i = 0; i < rgmSlider.slides[editSlideIndex].layers.length; i++) {
        addLayerEl(i);
    }
}

function newLayerClick() {
    newLayer = true;
    var layerCount = rgmSlider.slides[editSlideIndex].layers.length;
    editLayerIndex = layerCount;
    createLayerObj();


    //VISUAL FUNCTIONS
    openContentPane();
}

function layerDblClick() {
    newLayer = false;
    openContentPane();
}

function updateLayerContents(id) {
    switch (id) {
        case 'save-content' :
            rgmSlider.slides[editSlideIndex].layers[editLayerIndex].type = layerType.value
            rgmSlider.slides[editSlideIndex].layers[editLayerIndex].content = contentArea.value;
            rgmSlider.slides[editSlideIndex].layers[editLayerIndex].stationary = stationaryLayer.checked;
            rgmSlider.slides[editSlideIndex].layers[editLayerIndex].static = staticLayer.checked;
            if (layerType.value == 'image') {
                getImageOrientation(contentArea.value, function () {
                    reflectSlide();
                    closeContentPane();
                });
            } else {
                reflectSlide();
                closeContentPane();
            }
            break;
        case 'close-content' :
            if (newLayer) {
                rgmSlider.slides[editSlideIndex].layers.splice(editLayerIndex, 1);
            }
            closeContentPane();
            break;
    }
    //VISUAL

}

/** Too funky for my taste. Set img width & height to 100% and build div to match dimensions.
 *  On move, match dimensions unless key is held simultaneously. **/
/**
 * Determine if image is horizontal or vertical orientation
 * @param url
 * @param cb
 */
function getImageOrientation(url, cb) {
    var image = new Image();
    image.src = url;
    var o = '';
    image.addEventListener("load", function () {
        if (this.naturalWidth > this.naturalHeight) {
            o = 'horz';
        } else {
            o = 'vert';
        }
        rgmSlider.slides[editSlideIndex].layers[editLayerIndex].inner_classList = o;
        cb();
    });
}

/**
 * Enable save layer button only if change has been made.
 */
function checkLayerChange() {
    saveContent.disabled = true;
    contentArea.addEventListener('keyup', function () {
        if (contentArea.value) {
            saveContent.disabled = false;
        } else {
            saveContent.disabled = true;
        }
    });
    layerType.addEventListener('change', function () {
        saveContent.disabled = false;
    });
    //Will add for all objects in editBox
}

/**
 * Creates a new default layer, style and css object
 */
function createLayerObj() {
    $layerId = 'layer' + Date.now();
    rgmSlider.slides[editSlideIndex].layers[editLayerIndex] = new Layer(
        $layerId, new Date().toMysqlFormat(), '', '', 'text-middle',
        stationaryLayer.checked, staticLayer.checked,

        // layer_id, date_created, media_query, top, left, px_width, perc_width, max_width, min_width, px_height,
        // perc_height, max_height, min_height, transform, border, border_radius, background, opacity, text_align,
        // font_family, font_weight, font_size, font_style, color, z_index, layer_id
        [new CSS('style' + Date.now(), new Date().toMysqlFormat(), '', '0', '0', '', '50%', '', '', '', '50%', '', '', '', '', '', '', 1, '', '', '', '',
            '', 'white', '0', $layerId)],
        rgmSlider.slides[editSlideIndex].slide_id
    );
}

/**
 * Finds the index of a node with siblings
 * @param el
 * @returns {number}
 */
function nodeIndex(el) {
    var i = 0;
    while ((el = el.previousElementSibling) != null) {
        i++;
    }
    return i;
}

/**
 * Checks if variable is null, undefined or blank.
 * @param x
 * @returns {boolean}
 */
function isEmpty(x) {
    if (x === null || x === '' || x.typeOf === 'undefined') {
        return true;
    }
    return false;
}

/**
 * Returns text inside parenthesis in a string
 * @param x
 * @returns {Array|{index: number, input: string}|*|{index, content, options}}
 */
function textInParentheses(x) {
    var match = x.match(/\(([^)]+)\)/);
    return match && match[1];
}


//VISUAL -- Device tools
devicePreviewTool.addEventListener('click', function (e) {
    /*
     ** CSS rotation does not work. Add logic to reverse height/width on rotation click!!
     */
    if (e.target.classList.contains('tool-square')) {
        var deviceHeight;
        var deviceWidth;
        switch (e.target.id) {
            case 'mobile' :
                deviceEmulator.classList.add('mobile');
                deviceEmulator.classList.remove('tablet');
                deviceEmulator.classList.remove('desktop');
                deviceWidth = Math.ceil(deviceEmulator.offsetHeight / 1.779) + 'px';
                deviceDesc.innerText = 'Mobile (375x667)';
                deviceDetails.style.opacity = 1;
                break;
            case 'tablet' :
                deviceEmulator.classList.add('tablet');
                deviceEmulator.classList.remove('mobile');
                deviceEmulator.classList.remove('desktop');
                deviceWidth = Math.ceil(deviceEmulator.offsetHeight / 1.333) + 'px';
                deviceDesc.innerText = 'Tablet (768x1024)';
                deviceDetails.style.opacity = 1;
                break;
            case 'desktop' :
                deviceEmulator.classList.add('desktop');
                deviceEmulator.classList.remove('mobile');
                deviceEmulator.classList.remove('tablet');
                deviceEmulator.classList.remove('rotate-device');
                deviceWidth = '100%';
                deviceDetails.style.opacity = 0;
                break;
            case 'rotate-device' :
                if (!deviceEmulator.classList.contains('desktop')) {
                    deviceEmulator.classList.toggle('rotate-device');
                }
                if (deviceEmulator.classList.contains('rotate-device')) {
                    deviceOrientation.innerText = 'Landscape';
                } else {
                    deviceOrientation.innerText = 'Portrait';
                }
                break;
        }
        deviceEmulator.style.width = deviceWidth;
    }
});

/**
 * Adds
 * @param i
 */
function addSlideEl(ind) {
    var div = document.createElement('div');
    div.className = 'slide';
    div.id = rgmSlider.slides[ind].slide_id;
    if (!isEmpty(rgmSlider.slides[ind].bg)) {
        div.style.background = rgmSlider.slides[ind].bg;
        div.style.backgroundSize = 'cover';
    } else {
        div.style.background = 'darkslategrey';
    }
    slidesInnerWrap.appendChild(div);
}

/**
 *Adds the layer element to the deviceEmulator for preview.
 * @param layerObj
 */
function addLayerEl(ind) {
    var layer = rgmSlider.slides[editSlideIndex].layers[ind];
    var layerWrap = document.createElement('div');
    //add the resize borders to the layer wrap
    var layerResizeHandles = '<div id="side-top" class="side horz ns"></div>' +
        '<div id="side-top-right" class="side corner ne"></div>' +
        '<div id="side-right" class="side vert ew"></div>' +
        '<div id="side-bottom-right" class="side corner nw"></div>' +
        '<div id="side-bottom" class="side horz ns"></div>' +
        '<div id="side-bottom-left" class="side corner ne"></div>' +
        '<div id="side-left" class="side vert ew"></div>' +
        '<div id="side-top-left" class="side corner nw"></div>';
    layerWrap.classList.add('layer');
    layerWrap.id = layer.layer_id;

    // //Create layer options node for this layer.
    // addLayerOptEl(ind);

    // Add Styles
    //!!!!!!Layer/Style objects not working correctly from DB produced slides!!!!!!!  --> FIX <--
    applyStyles(layer.css[0], layerWrap, layer.static);

    layerWrap.innerHTML = layerResizeHandles;
    layerWrap.addEventListener('dblclick', layerDblClick);
    layerWrap.addEventListener('mousedown', startMove);
    layerWrap.addEventListener('mouseup', endMove);

    //Determine layer type and build layer content.
    //layer_id, date_created, type, content, inner_classList, stationary, static, css, slide_id
    var node = null;
    if (layer.type == 'image') {
        node = new Image();
        node.src = layer.content;
    } else {
        node = document.createElement('p');
        node.innerText = layer.content;
    }
    node.classList.add(layer.inner_classList);
    node.draggable = false;

    layerWrap.appendChild(node);
    deviceEmulator.appendChild(layerWrap);
}

// Deprecated. Will add buttons to layer-options-pane to alter current layer. ****************
// function addLayerOptEl(ind) {
//     var optEl = document.createElement('div');
//     optEl.classList.add('layer-option');
//     optEl.innerText = ind + 1;
//     optEl.addEventListener('click', function () {
//         //Open pane with additional options for layer
//         /*
//          * Options:
//          * max/main-height/width
//          * send back, forward, to front, to back
//          * delete layer
//          * Edit for this device/orientation mode only
//          *
//          * */
//     });
//
// }

function openSlideEditor() {
    slideEditor.classList.add('open');
}

function closeSlideEditor(e) {
    e.preventDefault();
    slideEditor.classList.remove('open');
}

function verifySave(e) {
    // e.preventDefault();
    // sliderCommit.classList.add('open');
    // var confirmStmt = '';
    // if (this.id == 'save-quit') {
    //     confirmStmt = 'Are you ready to save this slider? You\'ll be so glad you did!';
    // } else if (this.id == 'cancel-build') {
    //     confirmStmt = 'Are you sure you want to do this? This slider will go bye-bye forever!';
    // }
    // commitStmt.innerText = confirmStmt;
}

function openContentPane() {
    layerContentPane.classList.add('open');
    layerType.value = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].type;
    contentArea.value = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].content;
    stationaryLayer.checked = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].stationary;
    staticLayer.checked = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].static;
    checkLayerChange();
}

function closeContentPane(e) {
    layerContentPane.classList.remove('open');
    layers[editLayerIndex].classList.add('editable');
}


//MIRROR EDIT FUNCTIONS!


function startMove(e) {
    editLayerIndex = nodeIndex(this);
    sizeCalc(e);
    if (edgeDetect()) {
        deviceEmulator.addEventListener('mousemove', resizeLayer);
    } else {
        deviceEmulator.addEventListener('mousemove', moveLayer);
    }

    //VISUAL
    if (editable.length > 0) {
        editable[0].classList.remove('editable');
    }
    layers[editLayerIndex].classList.add('editable');
}

function endMove() {
    if (layerSide) {
        deviceEmulator.removeEventListener('mousemove', resizeLayer);
    } else {
        deviceEmulator.removeEventListener('mousemove', moveLayer);
    }
}

/**
 * Calculates size and position of layer and place of click
 * @param e
 */
function sizeCalc(e) {
    var bb = layers[editLayerIndex].getBoundingClientRect();
    mirWidth = deviceEmulator.offsetWidth;
    mirHeight = deviceEmulator.offsetHeight;
    moveClick = {
        bbTop: bb.top,
        bbRight: bb.right,
        bbBottom: bb.bottom,
        bbLeft: bb.left,
        oTop: layers[editLayerIndex].offsetTop,
        oLeft: layers[editLayerIndex].offsetLeft,
        height: layers[editLayerIndex].offsetHeight,
        width: layers[editLayerIndex].offsetWidth,
        xClick: e.clientX,
        yClick: e.clientY
    }
}

/**
 * Determines if mouse click is on edge of layerWrap
 * @returns {boolean}
 */
function edgeDetect() {
    layerSide = '';
    var top = (moveClick.yClick) - moveClick.bbTop < 7;
    var right = moveClick.bbRight - (moveClick.xClick) < 7;
    var bottom = moveClick.bbBottom - (moveClick.yClick) < 7;
    var left = (moveClick.xClick) - moveClick.bbLeft < 7;
    var sides = [top, right, bottom, left];
    if (!top && !right && !bottom && !left) {
        layerSide = null;
        return;
    }
    for (var i = 0; i < 4; i++) {
        if (sides[i] === true) {
            layerSide += SIDES[i];
        }
    }
    return true;
}

function moveLayer(e) {
    e.preventDefault();
    var OBJ = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].css[0];
    //Cancel mousemove event in case mouse moves out of elemnet on mouseUp
    deviceEmulator.addEventListener('click', function () {
        endMove();
    });
    var movedX = e.clientX - moveClick.xClick;
    var movedY = e.clientY - moveClick.yClick;
    var axisX = (((moveClick.oLeft + movedX) / mirWidth) * 100).toFixed(2) + '%';
    var axisY = (((moveClick.oTop + movedY) / mirHeight) * 100).toFixed(2) + '%';
    layers[editLayerIndex].style.left = axisX;
    OBJ.left = axisX;
    layers[editLayerIndex].style.top = axisY;
    OBJ.top = axisY;
}

function resizeLayer(e) {
    e.preventDefault();
    var OBJ = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].css[0];
    //Cancel mousemove event in case mouse moves out of elemnet on mouseUp
    deviceEmulator.addEventListener('click', function () {
        endMove();
    });

    //These work for bottom and right
    var movedX = e.clientX - moveClick.xClick;
    var movedY = e.clientY - moveClick.yClick;

    if (layerSide.includes("top")) {
        var height = (((moveClick.height - movedY) / mirHeight) * 100).toFixed(2) + '%';
        layers[editLayerIndex].style.height = height;
        OBJ.perc_height = height;
        var axisY = (((moveClick.oTop + movedY) / mirHeight) * 100).toFixed(2) + '%';
        layers[editLayerIndex].style.top = axisY;
        OBJ.top = axisY;
        // layers[editLayerIndex].style.height = moveClick.height - movedY + 'px';
        // layers[editLayerIndex].style.top = moveClick.oTop + movedY + 'px';
    }
    if (layerSide.includes("right")) {
        var width = (((moveClick.width + movedX) / mirWidth) * 100).toFixed(2) + '%';
        layers[editLayerIndex].style.width = width;
        OBJ.perc_width = width;
        // layers[editLayerIndex].style.width = moveClick.width + movedX + 'px';
    }
    if (layerSide.includes("bottom")) {
        height = (((moveClick.height + movedY) / mirHeight) * 100).toFixed(2) + '%';
        layers[editLayerIndex].style.height = height;
        OBJ.perc_height = height;
        // layers[editLayerIndex].style.height = moveClick.height + movedY + 'px';
    }
    if (layerSide.includes("left")) {
        width = (((moveClick.width - movedX) / mirWidth) * 100).toFixed(2) + '%';
        layers[editLayerIndex].style.width = width;
        OBJ.perc_width = width;
        var axisX = (((moveClick.oLeft + movedX) / mirWidth) * 100).toFixed(2) + '%';
        layers[editLayerIndex].style.left = axisX;
        OBJ.left = axisX;
        // layers[editLayerIndex].style.width = moveClick.width - movedX + 'px';
        // layers[editLayerIndex].style.left = moveClick.oLeft + movedX + 'px';
    }
}

/**
 * Aligns the layer inside the emulator
 * @param e
 */
function alignLayer(id) {
    var place = id.split('-')[1];
    var st = layers[editLayerIndex].style;
    moveClick.percY = ((moveClick.height / mirHeight) * 100).toFixed(2);
    moveClick.percX = ((moveClick.width / mirWidth) * 100).toFixed(2);
    var OBJ = rgmSlider.slides[editSlideIndex].layers[editLayerIndex].css[0];
    switch (place) {
        case "bottom":
            st.top = 100 - moveClick.percY + '%';
            OBJ.top = 100 - moveClick.percY + '%';
            break;
        case "middle":
            st.top = (50 - (moveClick.percY / 2)) + '%';
            OBJ.top = (50 - (moveClick.percY / 2)) + '%';
            break;
        case "top":
            st.top = '0%';
            OBJ.top = '0%';
            break;
        case "left":
            st.left = '0%';
            OBJ.left = '0%';
            break;
        case "center":
            st.left = (50 - (moveClick.percX / 2)) + '%';
            OBJ.left = (50 - (moveClick.percX / 2)) + '%';
            break;
        case "right":
            st.left = 100 - moveClick.percX + '%';
            OBJ.left = 100 - moveClick.percX + '%';
            break;
    }
}

/**
 * Aligns the text inside the layer
 * @param e
 */
function asAlignText(id) {
    //The <p> tag is 9th child after handles.
    var layer = rgmSlider.slides[editSlideIndex].layers[editLayerIndex];
    var cl = layers[editLayerIndex].children[8];
    var st = layers[editLayerIndex].style;
    switch (id) {
        case "text-left":
            st.textAlign = "left";
            layer.css[0].text_align = "left";
            break;
        case "text-center":
            st.textAlign = "center";
            layer.css[0].text_align = "center";
            break;
        case "text-right":
            st.textAlign = "right";
            layer.css[0].text_align = "right";
            break;
        case "text-justify":
            st.textAlign = "justify";
            layer.css[0].text_align = "justify";
            break;
        case "text-top":
            cl.classList.remove("text-middle");
            cl.classList.remove("text-bottom");
            cl.classList.add(id);
            layer.inner_classList = id;
            break;
        case "text-middle":
            cl.classList.remove("text-top");
            cl.classList.remove("text-bottom");
            cl.classList.add(id);
            layer.inner_classList = id;
            break;
        case "text-bottom":
            cl.classList.remove("text-top");
            cl.classList.remove("text-middle");
            cl.classList.add(id);
            layer.inner_classList = id;
            break;
    }
}


/**
 * DEV ONLY
 * Shows the json string in current state.
 */
function updateHiddenText() {
    var sliderJSON = document.getElementById('sliderJSON');
    sliderJSON.innerText = JSON.stringify(rgmSlider);
}

// });
