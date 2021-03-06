seajs.config({
  // 配置 shim 信息，这样我们就可以通过 require("jquery") 来获取 jQuery
  //plugins: ['shim']

  shim: {
    // for jquery
    jquery: {
      src: "../jquery/jquery-1.102.js"
      , exports: "jQuery"
    }
    ,handlebars: {
      src: "../handlebars/handlebars-v1.1.2.js"
      , exports: "Handlebars"
    }
    ,easing:{
      src: "../plugin/jquery.easing.1.3.js"
      , deps: ['jquery']
    }
    ,jscrollpane:{
      src: "../plugin/jquery.jscrollpane.js"
      , deps: ['jquery']
    }
    ,mousewheel:{
      src: "../plugin/jquery.mousewheel.js"
      , deps: ['jquery']
    }
    ,uiwidget: {
      src: "../plugin/jquery.ui.widget.js"
      , deps: ['jquery']
    }
    ,uicustom: {
        src: "../plugin/jquery-ui-1.10.3.custom.js"
        , deps: ['jquery']
    }
    ,fileupload:{
      src: "../plugin/jquery.fileupload.js"
      , deps: ['jquery','uiwidget']
    }
    ,form:{
      src: "../plugin/jquery.form.js"
      , deps: ['jquery']
    }
    ,validate:{
      src: "../plugin/jquery.validate.js"
      , deps: ['jquery']
    }
    ,raphael:{
      src: "../raphael/raphaeljs.min.js"
      , deps: ['eve']
      , exports: "Raphael"
    }
    ,"video-js":{
      src: "../video-js/video.js"
    }
    ,"flash-detect":{
      src: "../plugin/flash_detect_min.js"
    }
  }
  , alias: {
    api: '../api'
    , eve : '../raphael/eve.js'
    , api4sjht: '../api4sjht'
    , util: '../util/util'
    , panel: "../panel/panel"
    , autoComplete: '../autocomplete/autoComplete'
    , validator: '../validator/validator'
    , html2json: '../com/html2json'
    , tooltip: '../util/tooltip'
  }
});