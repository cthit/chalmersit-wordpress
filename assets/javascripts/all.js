(function($) {

  var version = '@VERSION',
    defaults = {
      exclude: [],
      excludeWithin: [],
      offset: 0,
      direction: 'top',
      // one of 'top' or 'left'
      scrollElement: null,
      // jQuery set of elements you wish to scroll (for $.smoothScroll).
      //  if null (default), $('html, body').firstScrollable() is used.
      scrollTarget: null,
      // only use if you want to override default behavior
      beforeScroll: function() {},
      // fn(opts) function to be called before scrolling occurs. "this" is the element(s) being scrolled
      afterScroll: function() {},
      // fn(opts) function to be called after scrolling occurs. "this" is the triggering element
      easing: 'swing',
      speed: 400,
      autoCoefficent: 2 // coefficient for "auto" speed
    },

    getScrollable = function(opts) {
      var scrollable = [],
        scrolled = false,
        dir = opts.dir && opts.dir == 'left' ? 'scrollLeft' : 'scrollTop';

      this.each(function() {

        if (this == document || this == window) {
          return;
        }
        var el = $(this);
        if (el[dir]() > 0) {
          scrollable.push(this);
        } else {
          // if scroll(Top|Left) === 0, nudge the element 1px and see if it moves
          el[dir](1);
          scrolled = el[dir]() > 0;
          if (scrolled) {
            scrollable.push(this);
          }
          // then put it back, of course
          el[dir](0);
        }
      });

      // If no scrollable elements, fall back to <body>,
      // if it's in the jQuery collection
      // (doing this because Safari sets scrollTop async,
      // so can't set it to 1 and immediately get the value.)
      if (!scrollable.length) {
        this.each(function(index) {
          if (this.nodeName === 'BODY') {
            scrollable = [this];
          }
        });
      }

      // Use the first scrollable element if we're calling firstScrollable()
      if (opts.el === 'first' && scrollable.length > 1) {
        scrollable = [scrollable[0]];
      }

      return scrollable;
    },
    isTouch = 'ontouchend' in document;

  $.fn.extend({
    scrollable: function(dir) {
      var scrl = getScrollable.call(this, {
        dir: dir
      });
      return this.pushStack(scrl);
    },
    firstScrollable: function(dir) {
      var scrl = getScrollable.call(this, {
        el: 'first',
        dir: dir
      });
      return this.pushStack(scrl);
    },

    smoothScroll: function(options) {
      options = options || {};
      var opts = $.extend({}, $.fn.smoothScroll.defaults, options),
        locationPath = $.smoothScroll.filterPath(location.pathname);

      this.unbind('click.smoothscroll').bind('click.smoothscroll', function(event) {
        var link = this,
          $link = $(this),
          exclude = opts.exclude,
          excludeWithin = opts.excludeWithin,
          elCounter = 0,
          ewlCounter = 0,
          include = true,
          clickOpts = {},
          hostMatch = ((location.hostname === link.hostname) || !link.hostname),
          pathMatch = opts.scrollTarget || ($.smoothScroll.filterPath(link.pathname) || locationPath) === locationPath,
          thisHash = escapeSelector(link.hash);

        if (!opts.scrollTarget && (!hostMatch || !pathMatch || !thisHash)) {
          include = false;
        } else {
          while (include && elCounter < exclude.length) {
            if ($link.is(escapeSelector(exclude[elCounter++]))) {
              include = false;
            }
          }
          while (include && ewlCounter < excludeWithin.length) {
            if ($link.closest(excludeWithin[ewlCounter++]).length) {
              include = false;
            }
          }
        }

        if (include) {
          event.preventDefault();

          $.extend(clickOpts, opts, {
            scrollTarget: opts.scrollTarget || thisHash,
            link: link
          });

          $.smoothScroll(clickOpts);
        }
      });

      return this;
    }
  });

  $.smoothScroll = function(options, px) {
    var opts, $scroller, scrollTargetOffset, speed, scrollerOffset = 0,
      offPos = 'offset',
      scrollDir = 'scrollTop',
      aniProps = {},
      aniOpts = {},
      scrollprops = [];


    if (typeof options === 'number') {
      opts = $.fn.smoothScroll.defaults;
      scrollTargetOffset = options;
    } else {
      opts = $.extend({}, $.fn.smoothScroll.defaults, options || {});
      if (opts.scrollElement) {
        offPos = 'position';
        if (opts.scrollElement.css('position') == 'static') {
          opts.scrollElement.css('position', 'relative');
        }
      }
    }

    opts = $.extend({
      link: null
    }, opts);
    scrollDir = opts.direction == 'left' ? 'scrollLeft' : scrollDir;

    if (opts.scrollElement) {
      $scroller = opts.scrollElement;
      scrollerOffset = $scroller[scrollDir]();
    } else {
      $scroller = $('html, body').firstScrollable();
    }

    // beforeScroll callback function must fire before calculating offset
    opts.beforeScroll.call($scroller, opts);

    scrollTargetOffset = (typeof options === 'number') ? options : px || ($(opts.scrollTarget)[offPos]() && $(opts.scrollTarget)[offPos]()[opts.direction]) || 0;

    aniProps[scrollDir] = scrollTargetOffset + scrollerOffset + opts.offset;
    speed = opts.speed;

    // automatically calculate the speed of the scroll based on distance / coefficient
    if (speed === 'auto') {

      // if aniProps[scrollDir] == 0 then we'll use scrollTop() value instead
      speed = aniProps[scrollDir] || $scroller.scrollTop();

      // divide the speed by the coefficient
      speed = speed / opts.autoCoefficent;
    }

    aniOpts = {
      duration: speed,
      easing: opts.easing,
      complete: function() {
        opts.afterScroll.call(opts.link, opts);
      }
    };

    if (opts.step) {
      aniOpts.step = opts.step;
    }

    if ($scroller.length) {
      $scroller.stop().animate(aniProps, aniOpts);
    } else {
      opts.afterScroll.call(opts.link, opts);
    }
  };

  $.smoothScroll.version = version;
  $.smoothScroll.filterPath = function(string) {
    return string.replace(/^\//, '').replace(/(index|default).[a-zA-Z]{3,4}$/, '').replace(/\/$/, '');
  };

  // default options
  $.fn.smoothScroll.defaults = defaults;

  function escapeSelector(str) {
    return str.replace(/(:|\.)/g, '\\$1');
  }

})(jQuery);
// Autosize 1.15.2 - jQuery plugin for textareas
// (c) 2013 Jack Moore - jacklmoore.com
// license: www.opensource.org/licenses/mit-license.php
(function($) {
  var
  defaults = {
    className: 'autosizejs',
    append: "",
    callback: false
  },
    hidden = 'hidden',
    borderBox = 'border-box',
    lineHeight = 'lineHeight',

    // border:0 is unnecessary, but avoids a bug in FireFox on OSX (http://www.jacklmoore.com/autosize#comment-851)
    copy = '<textarea tabindex="-1" style="position:absolute; top:-9999px; left:-9999px; right:auto; bottom:auto; border:0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden;"/>',

    // line-height is conditionally included because IE7/IE8/old Opera do not return the correct value.
    copyStyle = ['fontFamily', 'fontSize', 'fontWeight', 'fontStyle', 'letterSpacing', 'textTransform', 'wordSpacing', 'textIndent'],
    oninput = 'oninput',
    onpropertychange = 'onpropertychange',

    // to keep track which textarea is being mirrored when adjust() is called.
    mirrored,

    // the mirror element, which is used to calculate what size the mirrored element should be.
    mirror = $(copy).data('autosize', true)[0];

  // test that line-height can be accurately copied.
  mirror.style.lineHeight = '99px';
  if ($(mirror).css(lineHeight) === '99px') {
    copyStyle.push(lineHeight);
  }
  mirror.style.lineHeight = '';

  $.fn.autosize = function(options) {
    options = $.extend({}, defaults, options || {});

    if (mirror.parentNode !== document.body) {
      $(document.body).append(mirror);
    }

    return this.each(function() {
      var
      ta = this,
        $ta = $(ta),
        minHeight = $ta.height(),
        maxHeight = parseInt($ta.css('maxHeight'), 10),
        active, resize, boxOffset = 0,
        value = ta.value,
        callback = $.isFunction(options.callback);

      if ($ta.data('autosize')) {
        // exit if autosize has already been applied, or if the textarea is the mirror element.
        return;
      }

      if ($ta.css('box-sizing') === borderBox || $ta.css('-moz-box-sizing') === borderBox || $ta.css('-webkit-box-sizing') === borderBox) {
        boxOffset = $ta.outerHeight() - $ta.height();
      }

      resize = $ta.css('resize') === 'none' ? 'none' : 'horizontal';

      $ta.css({
        overflow: hidden,
        overflowY: hidden,
        wordWrap: 'break-word',
        resize: resize
      }).data('autosize', true);

      // Opera returns '-1px' when max-height is set to 'none'.
      maxHeight = maxHeight && maxHeight > 0 ? maxHeight : 9e4;

      function initMirror() {
        mirrored = ta;
        mirror.className = options.className;

        // mirror is a duplicate textarea located off-screen that
        // is automatically updated to contain the same text as the
        // original textarea.  mirror always has a height of 0.
        // This gives a cross-browser supported way getting the actual
        // height of the text, through the scrollTop property.
        $.each(copyStyle, function(i, val) {
          mirror.style[val] = $ta.css(val);
        });
      }

      // Using mainly bare JS in this function because it is going
      // to fire very often while typing, and needs to very efficient.
      function adjust() {
        var height, overflow, original;

        if (mirrored !== ta) {
          initMirror();
        }

        // the active flag keeps IE from tripping all over itself.  Otherwise
        // actions in the adjust function will cause IE to call adjust again.
        if (!active) {
          active = true;
          mirror.value = ta.value + options.append;
          mirror.style.overflowY = ta.style.overflowY;
          original = parseInt(ta.style.height, 10);

          // Update the width in case the original textarea width has changed
          mirror.style.width = $ta.width() + 'px';

          // Needed for IE7 to reliably return the correct scrollHeight
          mirror.scrollTop = 0;
          // Set a very high value for scrollTop to be sure the
          // mirror is scrolled all the way to the bottom.
          mirror.scrollTop = 9e4;
          height = mirror.scrollTop;
          // Note to self: replace the previous 3 lines with 'height = mirror.scrollHeight' when dropping IE7 support.
          if (height > maxHeight) {
            height = maxHeight;
            overflow = 'scroll';
          } else if (height < minHeight) {
            height = minHeight;
          }
          height += boxOffset;
          ta.style.overflowY = overflow || hidden;

          if (original !== height) {
            ta.style.height = height + 'px';
            if (callback) {
              options.callback.call(ta);
            }
          }

          // This small timeout gives IE a chance to draw it's scrollbar
          // before adjust can be run again (prevents an infinite loop).
          setTimeout(function() {
            active = false;
          }, 1);
        }
      }

      if (onpropertychange in ta) {
        if (oninput in ta) {
          // Detects IE9.  IE9 does not fire onpropertychange or oninput for deletions,
          // so binding to onkeyup to catch most of those occassions.  There is no way that I
          // know of to detect something like 'cut' in IE9.
          ta[oninput] = ta.onkeyup = adjust;
        } else {
          // IE7 / IE8
          ta[onpropertychange] = adjust;
        }
      } else {
        // Modern Browsers
        ta[oninput] = adjust;

        // The textarea overflow is now hidden, but Chrome doesn't reflow the text to account for the
        // new space made available by removing the scrollbars. This workaround causes Chrome to reflow the text.
        ta.value = '';
        ta.value = value;
      }

      $(window).resize(adjust);

      // Allow for manual triggering if needed.
      $ta.bind('autosize', adjust);

      // Call adjust in case the textarea already contains text.
      //adjust();
      $ta.bind("focus", adjust);
    });
  };
}(window.jQuery || window.Zepto));
// tipsy, facebook style tooltips for jquery
// version 1.0.0a
// (c) 2008-2010 jason frame [jason@onehackoranother.com]
// released under the MIT license
(function($) {

  function maybeCall(thing, ctx) {
    return (typeof thing == 'function') ? (thing.call(ctx)) : thing;
  };

  function isElementInDOM(ele) {
    while (ele = ele.parentNode) {
      if (ele == document) return true;
    }
    return false;
  };

  function Tipsy(element, options) {
    this.$element = $(element);
    this.options = options;
    this.enabled = true;
    this.fixTitle();
  };

  Tipsy.prototype = {
    show: function() {
      var title = this.getTitle();
      if (title && this.enabled) {
        var $tip = this.tip();

        $tip.find('.tipsy-inner')[this.options.html ? 'html' : 'text'](title);
        $tip[0].className = 'tipsy'; // reset classname in case of dynamic gravity
        $tip.remove().css({
          top: 0,
          left: 0,
          visibility: 'hidden',
          display: 'block'
        }).prependTo(document.body);

        var pos = $.extend({}, this.$element.offset(), {
          width: this.$element[0].offsetWidth,
          height: this.$element[0].offsetHeight
        });

        var actualWidth = $tip[0].offsetWidth,
          actualHeight = $tip[0].offsetHeight,
          gravity = maybeCall(this.options.gravity, this.$element[0]);

        var tp;
        switch (gravity.charAt(0)) {
        case 'n':
          tp = {
            top: pos.top + pos.height + this.options.offset,
            left: pos.left + pos.width / 2 - actualWidth / 2
          };
          break;
        case 's':
          tp = {
            top: pos.top - actualHeight - this.options.offset,
            left: pos.left + pos.width / 2 - actualWidth / 2
          };
          break;
        case 'e':
          tp = {
            top: pos.top + pos.height / 2 - actualHeight / 2,
            left: pos.left - actualWidth - this.options.offset
          };
          break;
        case 'w':
          tp = {
            top: pos.top + pos.height / 2 - actualHeight / 2,
            left: pos.left + pos.width + this.options.offset
          };
          break;
        }

        if (gravity.length == 2) {
          if (gravity.charAt(1) == 'w') {
            tp.left = pos.left + pos.width / 2 - 15;
          } else {
            tp.left = pos.left + pos.width / 2 - actualWidth + 15;
          }
        }

        $tip.css(tp).addClass('tipsy-' + gravity);
        $tip.find('.tipsy-arrow')[0].className = 'tipsy-arrow tipsy-arrow-' + gravity.charAt(0);
        if (this.options.className) {
          $tip.addClass(maybeCall(this.options.className, this.$element[0]));
        }

        if (this.options.fade) {
          $tip.stop().css({
            opacity: 0,
            display: 'block',
            visibility: 'visible'
          }).animate({
            opacity: this.options.opacity
          });
        } else {
          $tip.css({
            visibility: 'visible',
            opacity: this.options.opacity
          });
        }
      }
    },

    hide: function() {
      if (this.options.fade) {
        this.tip().stop().fadeOut(function() {
          $(this).remove();
        });
      } else {
        this.tip().remove();
      }
    },

    fixTitle: function() {
      var $e = this.$element;
      if ($e.attr('title') || typeof($e.attr('original-title')) != 'string') {
        $e.attr('original-title', $e.attr('title') || '').removeAttr('title');
      }
    },

    getTitle: function() {
      var title, $e = this.$element,
        o = this.options;
      this.fixTitle();
      var title, o = this.options;
      if (typeof o.title == 'string') {
        title = $e.attr(o.title == 'title' ? 'original-title' : o.title);
      } else if (typeof o.title == 'function') {
        title = o.title.call($e[0]);
      }
      title = ('' + title).replace(/(^\s*|\s*$)/, "");
      return title || o.fallback;
    },

    tip: function() {
      if (!this.$tip) {
        this.$tip = $('<div class="tipsy"></div>').html('<div class="tipsy-arrow"></div><div class="tipsy-inner"></div>');
        this.$tip.data('tipsy-pointee', this.$element[0]);
      }
      return this.$tip;
    },

    validate: function() {
      if (!this.$element[0].parentNode) {
        this.hide();
        this.$element = null;
        this.options = null;
      }
    },

    enable: function() {
      this.enabled = true;
    },
    disable: function() {
      this.enabled = false;
    },
    toggleEnabled: function() {
      this.enabled = !this.enabled;
    }
  };

  $.fn.tipsy = function(options) {

    if (options === true) {
      return this.data('tipsy');
    } else if (typeof options == 'string') {
      var tipsy = this.data('tipsy');
      if (tipsy) tipsy[options]();
      return this;
    }

    options = $.extend({}, $.fn.tipsy.defaults, options);

    function get(ele) {
      var tipsy = $.data(ele, 'tipsy');
      if (!tipsy) {
        tipsy = new Tipsy(ele, $.fn.tipsy.elementOptions(ele, options));
        $.data(ele, 'tipsy', tipsy);
      }
      return tipsy;
    }

    function enter() {
      var tipsy = get(this);
      tipsy.hoverState = 'in';
      if (options.delayIn == 0) {
        tipsy.show();
      } else {
        tipsy.fixTitle();
        setTimeout(function() {
          if (tipsy.hoverState == 'in') tipsy.show();
        }, options.delayIn);
      }
    };

    function leave() {
      var tipsy = get(this);
      tipsy.hoverState = 'out';
      if (options.delayOut == 0) {
        tipsy.hide();
      } else {
        setTimeout(function() {
          if (tipsy.hoverState == 'out') tipsy.hide();
        }, options.delayOut);
      }
    };

    if (!options.live) this.each(function() {
      get(this);
    });

    if (options.trigger != 'manual') {
      var binder = options.live ? 'live' : 'bind',
        eventIn = options.trigger == 'hover' ? 'mouseenter' : 'focus',
        eventOut = options.trigger == 'hover' ? 'mouseleave' : 'blur';
      this[binder](eventIn, enter)[binder](eventOut, leave);
    }

    return this;

  };

  $.fn.tipsy.defaults = {
    className: null,
    delayIn: 0,
    delayOut: 0,
    fade: false,
    fallback: '',
    gravity: 'n',
    html: false,
    live: false,
    offset: 0,
    opacity: 0.8,
    title: 'title',
    trigger: 'hover'
  };

  $.fn.tipsy.revalidate = function() {
    $('.tipsy').each(function() {
      var pointee = $.data(this, 'tipsy-pointee');
      if (!pointee || !isElementInDOM(pointee)) {
        $(this).remove();
      }
    });
  };

  // Overwrite this method to provide options on a per-element basis.
  // For example, you could store the gravity in a 'tipsy-gravity' attribute:
  // return $.extend({}, options, {gravity: $(ele).attr('tipsy-gravity') || 'n' });
  // (remember - do not modify 'options' in place!)
  $.fn.tipsy.elementOptions = function(ele, options) {
    return $.metadata ? $.extend({}, options, $(ele).metadata()) : options;
  };

  $.fn.tipsy.autoNS = function() {
    return $(this).offset().top > ($(document).scrollTop() + $(window).height() / 2) ? 's' : 'n';
  };

  $.fn.tipsy.autoWE = function() {
    return $(this).offset().left > ($(document).scrollLeft() + $(window).width() / 2) ? 'e' : 'w';
  };

  /**
   * yields a closure of the supplied parameters, producing a function that takes
   * no arguments and is suitable for use as an autogravity function like so:
   *
   * @param margin (int) - distance from the viewable region edge that an
   *        element should be before setting its tooltip's gravity to be away
   *        from that edge.
   * @param prefer (string, e.g. 'n', 'sw', 'w') - the direction to prefer
   *        if there are no viewable region edges effecting the tooltip's
   *        gravity. It will try to vary from this minimally, for example,
   *        if 'sw' is preferred and an element is near the right viewable
   *        region edge, but not the top edge, it will set the gravity for
   *        that element's tooltip to be 'se', preserving the southern
   *        component.
   */
  $.fn.tipsy.autoBounds = function(margin, prefer) {
    return function() {
      var dir = {
        ns: prefer[0],
        ew: (prefer.length > 1 ? prefer[1] : false)
      },
        boundTop = $(document).scrollTop() + margin,
        boundLeft = $(document).scrollLeft() + margin,
        $this = $(this);

      if ($this.offset().top < boundTop) dir.ns = 'n';
      if ($this.offset().left < boundLeft) dir.ew = 'w';
      if ($(window).width() + $(document).scrollLeft() - $this.offset().left < margin) dir.ew = 'e';
      if ($(window).height() + $(document).scrollTop() - $this.offset().top < margin) dir.ns = 's';

      return dir.ns + (dir.ew ? dir.ew : '');
    }
  };

})(jQuery);
/* =========================================================
 * bootstrap-modal.js v3.0.0
 * http://twitter.github.com/bootstrap/javascript.html#modals
 * =========================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */


!function ($) {

  "use strict"; // jshint ;_;


 /* MODAL CLASS DEFINITION
  * ====================== */

  var Modal = function (element, options) {
    this.options = options
    this.$element = $(element)
      .delegate('[data-dismiss="modal"]', 'click.dismiss.modal', $.proxy(this.hide, this))
    this.options.remote && this.$element.find('.modal-body').load(this.options.remote)
  }

  Modal.prototype = {

      constructor: Modal

    , toggle: function () {
        return this[!this.isShown ? 'show' : 'hide']()
      }

    , show: function () {
        var that = this
          , e = $.Event('show')

        this.$element.trigger(e)

        if (this.isShown || e.isDefaultPrevented()) return

        this.isShown = true

        this.escape()

        this.backdrop(function () {
          var transition = $.support.transition && that.$element.hasClass('fade')

          if (!that.$element.parent().length) {
            that.$element.appendTo(document.body) //don't move modals dom position
          }

          that.$element.show()

          if (transition) {
            that.$element[0].offsetWidth // force reflow
          }

          that.$element
            .addClass('in')
            .attr('aria-hidden', false)

          that.enforceFocus()

          transition ?
            that.$element.one($.support.transition.end, function () { that.$element.focus().trigger('shown') }) :
            that.$element.focus().trigger('shown')

        })
      }

    , hide: function (e) {
        e && e.preventDefault()

        var that = this

        e = $.Event('hide')

        this.$element.trigger(e)

        if (!this.isShown || e.isDefaultPrevented()) return

        this.isShown = false

        this.escape()

        $(document).off('focusin.modal')

        this.$element
          .removeClass('in')
          .attr('aria-hidden', true)

        $.support.transition && this.$element.hasClass('fade') ?
          this.hideWithTransition() :
          this.hideModal()
      }

    , enforceFocus: function () {
        var that = this
        $(document).on('focusin.modal', function (e) {
          if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
            that.$element.focus()
          }
        })
      }

    , escape: function () {
        var that = this
        if (this.isShown && this.options.keyboard) {
          this.$element.on('keyup.dismiss.modal', function ( e ) {
            e.which == 27 && that.hide()
          })
        } else if (!this.isShown) {
          this.$element.off('keyup.dismiss.modal')
        }
      }

    , hideWithTransition: function () {
        var that = this
          , timeout = setTimeout(function () {
              that.$element.off($.support.transition.end)
              that.hideModal()
            }, 500)

        this.$element.one($.support.transition.end, function () {
          clearTimeout(timeout)
          that.hideModal()
        })
      }

    , hideModal: function () {
        var that = this
        this.$element.hide()
        this.backdrop(function () {
          that.removeBackdrop()
          that.$element.trigger('hidden')
        })
      }

    , removeBackdrop: function () {
        this.$backdrop && this.$backdrop.remove()
        this.$backdrop = null
      }

    , backdrop: function (callback) {
        var that = this
          , animate = this.$element.hasClass('fade') ? 'fade' : ''

        if (this.isShown && this.options.backdrop) {
          var doAnimate = $.support.transition && animate

          this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
            .appendTo(document.body)

          this.$backdrop.click(
            this.options.backdrop == 'static' ?
              $.proxy(this.$element[0].focus, this.$element[0])
            : $.proxy(this.hide, this)
          )

          if (doAnimate) this.$backdrop[0].offsetWidth // force reflow

          this.$backdrop.addClass('in')


          if (!callback) return

          doAnimate ?
            this.$backdrop.one($.support.transition.end, callback) :
            callback()

        } else if (!this.isShown && this.$backdrop) {
          this.$backdrop.removeClass('in')

          $.support.transition && this.$element.hasClass('fade')?
            this.$backdrop.one($.support.transition.end, callback) :
            callback()

        } else if (callback) {
          callback()
        }
      }
  }


 /* MODAL PLUGIN DEFINITION
  * ======================= */

  var old = $.fn.modal

  $.fn.modal = function (option) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('modal')
        , options = $.extend({}, $.fn.modal.defaults, $this.data(), typeof option == 'object' && option)
      if (!data) $this.data('modal', (data = new Modal(this, options)))
      if (typeof option == 'string') data[option]()
      else if (options.show) data.show()
    })
  }

  $.fn.modal.defaults = {
      backdrop: true
    , keyboard: true
    , show: true
  }

  $.fn.modal.Constructor = Modal


 /* MODAL NO CONFLICT
  * ================= */

  $.fn.modal.noConflict = function () {
    $.fn.modal = old
    return this
  }


 /* MODAL DATA-API
  * ============== */

  $(document).on('click.modal.data-api', '[data-toggle="modal"]', function (e) {
    var $this = $(this)
      , href = $this.attr('href')
      , $target = $($this.attr('data-target') || (href && href.replace(/.*(?=#[^\s]+$)/, ''))) //strip for ie7
      , option = $target.data('modal') ? 'toggle' : $.extend({ remote:!/#/.test(href) && href }, $target.data(), $this.data())

    e.preventDefault()

    $target
      .modal(option)
      .one('hide', function () {
        $this.focus()
      })
    })

    var $body = $(document.body)
      .on('shown', '.modal', function () { $body.addClass('modal-open') })
      .on('hidden', '.modal', function () { $body.removeClass('modal-open') })

}(window.jQuery);
(function($){
	if(!window.localStorage)
		return false;

	var store = window.localStorage;

	$.persist = {
		clear: function() {
			store.removeItem("period");
			store.removeItem("year");
		},

		save: function(data) {
			store.setItem("period", data.period);
			store.setItem("year", data.year);

			console.log("Saved: ", data);
		},

		load: function(opts) {
			var year = store.getItem("year"),
				period = store.getItem("period");

			opts.year.filter("[value='"+year+"']").attr("checked", true);
			opts.period.filter("[value='"+period+"']").attr("checked", true);

			opts.callback();
		}
	};

	$.fn.persist = function(options) {

		return this.each(function() {
			var $year = $(this).find(options.year),
				$period = $(this).find(options.period),
				save = $(options.save),
				clear = $(options.clear);

			$.persist.load({
				year: $year,
				period: $period,
				callback: options.callback
			});

			$year.add($period).on("change", function(evt) {
				save.attr("disabled", false);
				options.callback(evt);
			});

			save.on("click", function(evt) {
				evt.preventDefault();
				$.persist.save({
					year: parseInt($year.filter(":checked").val()),
					period: parseInt($period.filter(":checked").val())
				});

				clear.attr("disabled", false);
				save.attr("disabled", true);
			});

			clear.on("click", function(evt) {
				evt.preventDefault();
				$.persist.clear();
				clear.attr("disabled", true);
			});

		});

	}

})(jQuery);

$(function() {

	var $form = $("#courses-form"),
		$inputs = $form.find("input"),
		$container = $("#courses-container");

	$form.persist({
		year: "#years-field input",
		period: "#periods-field input",
		save: "#save-config",
		clear: "#clear-config",
		callback: function(evt) {

			$form.find(".loading").fadeIn();

			var data = {
				action: "it_courses_filter",
				year: parseInt($form.find("#years-field input").filter(":checked").val()),
				period: parseInt($form.find("#periods-field input").filter(":checked").val())
			};

			$.get(pageOptions.ajaxURL, data, function(response) {
				$form.find(".loading").hide();
				$container.html(response);
			});

	}
	});

});
/*
	Javascript functions for Chalmers.it

-------------------------------------------- */

var Chalmers = (function(it) {
	var root = it || {};


	root.linkify = function(text) {
		if (text) {
			text = text.replace(
				/((https?\:\/\/)|(www\.))(\S+)(\w{2,4})(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi,
				function(url){
					var full_url = url;
					if (!full_url.match('^https?:\/\/')) {
						full_url = 'http://' + full_url;
					}

					return '<a href="' + full_url + '">' + url + '</a>';
				});
		    }

		    return text;
	};

	return root;

})(window.Chalmers);

/**
*	Quick and dirty jQuery animation helper plugin
*
*	Usage:
*	$("#element").jb_animate("<animation name>")
*
*	The single parameter, animation name, should be defined
*	as a CSS class. The class is applied to the element. The class
*	should include a CSS animation. Example:
*
*	.shake {
*		-webkit-animation: shake 1s ease-out;
* 	}
*/
(function($){
	$.fn.jb_animate = function(animation) {
		return this.each(function() {
			$(this).addClass(animation)
			.on("webkitAnimationEnd animationend", function() {
				$(this).removeClass(animation);
			})
		});
	};
})(jQuery);


/*
	Simple jQuery tabs plugin
*/
(function($){

	$.fn.tabs = function(options){
		if(options.tabContainer === undefined){
			return false;
		}

		var nav = $(this),
			settings = {
				activeClass: "current",
				useHash: true,
				hashPrefix: "tab-",
				el: "a"
			};

		settings = $.extend({}, settings, options);

		return this.each(function(){
			var $tabContainers = $(settings.tabContainer),
				hash = location.hash && ("#"+ settings.hashPrefix + location.hash.replace("#","")),
				which = (settings.useHash && hash) || ":first";

			$tabContainers.hide().filter(which).show();

			$(this).find(settings.el).on("click", function(evt){
				evt.preventDefault();
				var tab = $tabContainers.filter(this.hash);

				$tabContainers.hide();
				tab.show();

				nav.find(settings.el).removeClass(settings.activeClass);
				$(this).addClass(settings.activeClass);
				location.hash = tab.attr("id").replace(settings.hashPrefix, "");

			});

			if(which == ":first")
				$(this).find(settings.el).filter(which).click();
			else
				$(this).find(settings.el).filter('[href="'+which+'"]').click();

		});
	};

})(jQuery);


/*
	Load more posts dynamically from frontpage
*/
(function($){
	$.loadMorePosts = function(container) {

		var $container = $(container),
			pageNum = pageOptions.startPage + 1,
			max = pageOptions.maxPages,
			nextLink = pageOptions.nextLink;

		$container.find("footer").remove();

		if(pageNum <= max) {
			$container
				.append('<div class="post-placeholder-'+ pageNum +'"></div>')
				.append('<footer><a href="#" class="btn wide">Fler nyheter</a></footer>');
		}

		$(".news footer a").on("click", function(evt) {
			evt.preventDefault();
			var $button = $(this);

			if(pageNum <= max) {
				$button.text("Laddar ...");

				$(".post-placeholder-"+pageNum).load(nextLink + " [role='article']", function() {
					pageNum++;
					nextLink = nextLink.replace(/\/page\/[0-9]?/, '/page/'+ pageNum);

					$('.news > footer').before('<div class="post-placeholder-'+ pageNum +'"></div>');

					if(pageNum <= max) {
						$button.text("Fler nyheter");
					}
					else {
						$button.text("Inga fler nyheter finns").attr("disabled", true);
					}

				});
			}
		});

	};
})(jQuery);

$(function() {

	/* Front page functions */

	// Shake the login form when clicking the 'Log in' button
	$(".home #login-btn").on("click", function(evt) {
		evt.preventDefault();
		$(".user-area form")
			.jb_animate("shake")
			.find("input[type='text']:first")
			.focus();
	});


	// Create modal for the avatar upload on Profile page
	$("#user-avatar-link").on("click", function(evt){
		evt.preventDefault();
		var iframe = $("<iframe />", {
			src: this.href,
			scrolling: "no",
			id: "avatar-iframe",
			frameborder: "no",
			allowTransparency: "true"
		});

		$("#avatar-modal").append(iframe).modal("show");


		// When clicking the 'close' link in the last step,
		// make sure to hide the modal correctly.

		iframe.on("load", function(evt) {
			$(this).contents().find("#user-avatar-step3-close")
				.removeAttr("onclick")
				.on("click", function(evt){
					evt.preventDefault();
					$("#avatar-modal").modal("hide");
				});
		});
	});


	// Wipe avatar modal on hide
	$("#avatar-modal").on("hidden", function() {
		$(this).find("iframe").remove();
	});

	// Set up smooth scrolling links
	$(".smooth").smoothScroll({
		offset: -100
	});

	$(".comment-action").smoothScroll({
		afterScroll: function() {
			$("#comment").focus();
		}
	});

	// Setup the main navigation toggle when on smaller screens
	$("#main-nav-toggle").on("click", function(evt) {
		$(".inner-bar").slideToggle(200);
	});

	// Show comment controls on comment textarea focus
	$("#comment")
	.on("focus", function(evt){
		$(this).next(".comment-submit").show().find("#submit").attr("disabled", true);
	})
	.on("input", function(evt) {
		var text = $(this).val(),
			$submit = $(this).next(".comment-submit").find("#submit");

		$submit.attr("disabled", (text === ""));
	});

	// Auto growing textareas
	$(".autosize").autosize({append: "\n\n\n"});

	$.fn.tipsy.elementOptions = function(ele, options) {
	  return $.extend({}, options, {
			gravity: $(ele).data('tooltip-gravity') || 's',
			offset: parseInt($(ele).data('tooltip-offset')) || 0
		});
	};

	// Add support for touch devices for the 'Tools' main nav menu
	if("ontouchstart" in document) {
		$("#tools-menu-trigger").on("touchstart", function(evt) {
			var dropdown = $(this).next(".dropdown-sub");
			if(dropdown.hasClass("open")) {
				dropdown.removeClass("open").slideUp(100);
			}
			else {
				dropdown.addClass("open").slideDown(200);
			}
		});
	}

	// Tooltips
	$('[rel="tooltip"]').tipsy({
		gravity: 's',
		offset: 5
	});

	// Borders on images in posts
	$("article .article-content img:not(.avatar)").each(function() {
		if($(this).parent().is("figure")) {
			$(this).parent().addClass("subtle-border");
		}
	});

	// Set up tabs
	$(".tabs").tabs({
		tabContainer: ".tab-container > div",
		activeClass: "tab-current"
	});


	// Pressing 'Esc' when in search field in header should blur the field
	$("[role='search'] input").on("keyup", function(evt) {
		if(evt.which == 27)
			this.blur();
	});


	// Show Twitter timeline on frontpage

	$('.it_twitter').each(function(){
		var that = this;
		var twQContent = $(this).children('meta[name=twitter-content]').attr("content");
		var twQType = $(this).children('meta[name=twitter-type]').attr("content");
		var twQSize = $(this).children('meta[name=twitter-count]').attr("content");
		var twitterUrl = "https://";
		if(twQType === "user"){
			twitterUrl += "api.twitter.com/1/statuses/user_timeline/";
		} // Add hashtag support here

		twitterUrl += twQContent+".json?callback=?";
		console.log(twitterUrl);
		$.getJSON(twitterUrl, function(json, status, xhr) {
			var $list = $("<ul />", {
				"class": "list"
			});

			if(json != null) {
				$.each(json, function(i) {
					var date = new Date(this.created_at),
						text = "<p>" + Chalmers.linkify(this.text) + "</p><time>"+ date.toDateString() +"</time>";
					var element = $("<li />", {
						"html": text
					});

					$list.append(element);

					return i<(twQSize -1);
				});


			}
			else {
				$list.html("<li>Kunde inte hämta tweets från Twitter</li>")
			}

			$(that).find('#tweet-list').append($list);

		});
	});

});
