!function(e,n){if("object"==typeof exports&&"object"==typeof module)module.exports=n(require("preact/compat"));else if("function"==typeof define&&define.amd)define(["preact/compat"],n);else{var t="object"==typeof exports?n(require("preact/compat")):n(e.PreactCompat);for(var r in t)("object"==typeof exports?exports:e)[r]=t[r]}}(Depicter,(e=>(()=>{"use strict";var n={314:n=>{n.exports=e}},t={};function r(e){var a=t[e];if(void 0!==a)return a.exports;var o=t[e]={exports:{}};return n[e](o,o.exports,r),o.exports}r.d=(e,n)=>{for(var t in n)r.o(n,t)&&!r.o(e,t)&&Object.defineProperty(e,t,{enumerable:!0,get:n[t]})},r.o=(e,n)=>Object.prototype.hasOwnProperty.call(e,n),r.r=e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})};var a={};return(()=>{r.r(a),r.d(a,{dpcCounter:()=>ue});var e=r(314),n={update:null,begin:null,loopBegin:null,changeBegin:null,change:null,changeComplete:null,loopComplete:null,complete:null,loop:1,direction:"normal",autoplay:!0,timelineOffset:0},t={duration:1e3,delay:0,endDelay:0,easing:"easeOutElastic(1, .5)",round:0},o=["translateX","translateY","translateZ","rotate","rotateX","rotateY","rotateZ","scale","scaleX","scaleY","scaleZ","skew","skewX","skewY","perspective","matrix","matrix3d"],u={CSS:{},springs:{}};function i(e,n,t){return Math.min(Math.max(e,n),t)}function c(e,n){return e.indexOf(n)>-1}function s(e,n){return e.apply(null,n)}var f={arr:function(e){return Array.isArray(e)},obj:function(e){return c(Object.prototype.toString.call(e),"Object")},pth:function(e){return f.obj(e)&&e.hasOwnProperty("totalLength")},svg:function(e){return e instanceof SVGElement},inp:function(e){return e instanceof HTMLInputElement},dom:function(e){return e.nodeType||f.svg(e)},str:function(e){return"string"==typeof e},fnc:function(e){return"function"==typeof e},und:function(e){return void 0===e},nil:function(e){return f.und(e)||null===e},hex:function(e){return/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(e)},rgb:function(e){return/^rgb/.test(e)},hsl:function(e){return/^hsl/.test(e)},col:function(e){return f.hex(e)||f.rgb(e)||f.hsl(e)},key:function(e){return!n.hasOwnProperty(e)&&!t.hasOwnProperty(e)&&"targets"!==e&&"keyframes"!==e}};function l(e){var n=/\(([^)]+)\)/.exec(e);return n?n[1].split(",").map((function(e){return parseFloat(e)})):[]}function d(e,n){var t=l(e),r=i(f.und(t[0])?1:t[0],.1,100),a=i(f.und(t[1])?100:t[1],.1,100),o=i(f.und(t[2])?10:t[2],.1,100),c=i(f.und(t[3])?0:t[3],.1,100),s=Math.sqrt(a/r),d=o/(2*Math.sqrt(a*r)),p=d<1?s*Math.sqrt(1-d*d):0,v=d<1?(d*s-c)/p:-c+s;function m(e){var t=n?n*e/1e3:e;return t=d<1?Math.exp(-t*d*s)*(1*Math.cos(p*t)+v*Math.sin(p*t)):(1+v*t)*Math.exp(-t*s),0===e||1===e?e:1-t}return n?m:function(){var n=u.springs[e];if(n)return n;for(var t=1/6,r=0,a=0;;)if(1===m(r+=t)){if(++a>=16)break}else a=0;var o=r*t*1e3;return u.springs[e]=o,o}}function p(e){return void 0===e&&(e=10),function(n){return Math.ceil(i(n,1e-6,1)*e)*(1/e)}}var v,m,h=function(){var e=.1;function n(e,n){return 1-3*n+3*e}function t(e,n){return 3*n-6*e}function r(e){return 3*e}function a(e,a,o){return((n(a,o)*e+t(a,o))*e+r(a))*e}function o(e,a,o){return 3*n(a,o)*e*e+2*t(a,o)*e+r(a)}return function(n,t,r,u){if(0<=n&&n<=1&&0<=r&&r<=1){var i=new Float32Array(11);if(n!==t||r!==u)for(var c=0;c<11;++c)i[c]=a(c*e,n,r);return function(c){return n===t&&r===u||0===c||1===c?c:a(function(t){for(var u=0,c=1;10!==c&&i[c]<=t;++c)u+=e;--c;var s=u+(t-i[c])/(i[c+1]-i[c])*e,f=o(s,n,r);return f>=.001?function(e,n,t,r){for(var u=0;u<4;++u){var i=o(n,t,r);if(0===i)return n;n-=(a(n,t,r)-e)/i}return n}(t,s,n,r):0===f?s:function(e,n,t,r,o){var u,i,c=0;do{(u=a(i=n+(t-n)/2,r,o)-e)>0?t=i:n=i}while(Math.abs(u)>1e-7&&++c<10);return i}(t,u,u+e,n,r)}(c),t,u)}}}}(),g=(v={linear:function(){return function(e){return e}}},m={Sine:function(){return function(e){return 1-Math.cos(e*Math.PI/2)}},Circ:function(){return function(e){return 1-Math.sqrt(1-e*e)}},Back:function(){return function(e){return e*e*(3*e-2)}},Bounce:function(){return function(e){for(var n,t=4;e<((n=Math.pow(2,--t))-1)/11;);return 1/Math.pow(4,3-t)-7.5625*Math.pow((3*n-2)/22-e,2)}},Elastic:function(e,n){void 0===e&&(e=1),void 0===n&&(n=.5);var t=i(e,1,10),r=i(n,.1,2);return function(e){return 0===e||1===e?e:-t*Math.pow(2,10*(e-1))*Math.sin((e-1-r/(2*Math.PI)*Math.asin(1/t))*(2*Math.PI)/r)}}},["Quad","Cubic","Quart","Quint","Expo"].forEach((function(e,n){m[e]=function(){return function(e){return Math.pow(e,n+2)}}})),Object.keys(m).forEach((function(e){var n=m[e];v["easeIn"+e]=n,v["easeOut"+e]=function(e,t){return function(r){return 1-n(e,t)(1-r)}},v["easeInOut"+e]=function(e,t){return function(r){return r<.5?n(e,t)(2*r)/2:1-n(e,t)(-2*r+2)/2}},v["easeOutIn"+e]=function(e,t){return function(r){return r<.5?(1-n(e,t)(1-2*r))/2:(n(e,t)(2*r-1)+1)/2}}})),v);function y(e,n){if(f.fnc(e))return e;var t=e.split("(")[0],r=g[t],a=l(e);switch(t){case"spring":return d(e,n);case"cubicBezier":return s(h,a);case"steps":return s(p,a);default:return s(r,a)}}function b(e){try{return document.querySelectorAll(e)}catch(e){return}}function x(e,n){for(var t=e.length,r=arguments.length>=2?arguments[1]:void 0,a=[],o=0;o<t;o++)if(o in e){var u=e[o];n.call(r,u,o,e)&&a.push(u)}return a}function M(e){return e.reduce((function(e,n){return e.concat(f.arr(n)?M(n):n)}),[])}function w(e){return f.arr(e)?e:(f.str(e)&&(e=b(e)||e),e instanceof NodeList||e instanceof HTMLCollection?[].slice.call(e):[e])}function k(e,n){return e.some((function(e){return e===n}))}function C(e){var n={};for(var t in e)n[t]=e[t];return n}function D(e,n){var t=C(e);for(var r in e)t[r]=n.hasOwnProperty(r)?n[r]:e[r];return t}function O(e,n){var t=C(e);for(var r in n)t[r]=f.und(e[r])?n[r]:e[r];return t}function P(e){var n=/[+-]?\d*\.?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?(%|px|pt|em|rem|in|cm|mm|ex|ch|pc|vw|vh|vmin|vmax|deg|rad|turn)?$/.exec(e);if(n)return n[1]}function I(e,n){return f.fnc(e)?e(n.target,n.id,n.total):e}function T(e,n){return e.getAttribute(n)}function N(e,n,t){if(k([t,"deg","rad","turn"],P(n)))return n;var r=u.CSS[n+t];if(!f.und(r))return r;var a=document.createElement(e.tagName),o=e.parentNode&&e.parentNode!==document?e.parentNode:document.body;o.appendChild(a),a.style.position="absolute",a.style.width=100+t;var i=100/a.offsetWidth;o.removeChild(a);var c=i*parseFloat(n);return u.CSS[n+t]=c,c}function B(e,n,t){if(n in e.style){var r=n.replace(/([a-z])([A-Z])/g,"$1-$2").toLowerCase(),a=e.style[n]||getComputedStyle(e).getPropertyValue(r)||"0";return t?N(e,a,t):a}}function S(e,n){return f.dom(e)&&!f.inp(e)&&(!f.nil(T(e,n))||f.svg(e)&&e[n])?"attribute":f.dom(e)&&k(o,n)?"transform":f.dom(e)&&"transform"!==n&&B(e,n)?"css":null!=e[n]?"object":void 0}function F(e){if(f.dom(e)){for(var n,t=e.style.transform||"",r=/(\w+)\(([^)]*)\)/g,a=new Map;n=r.exec(t);)a.set(n[1],n[2]);return a}}function j(e,n,t,r){switch(S(e,n)){case"transform":return function(e,n,t,r){var a=c(n,"scale")?1:0+function(e){return c(e,"translate")||"perspective"===e?"px":c(e,"rotate")||c(e,"skew")?"deg":void 0}(n),o=F(e).get(n)||a;return t&&(t.transforms.list.set(n,o),t.transforms.last=n),r?N(e,o,r):o}(e,n,r,t);case"css":return B(e,n,t);case"attribute":return T(e,n);default:return e[n]||0}}function E(e,n){var t=/^(\*=|\+=|-=)/.exec(e);if(!t)return e;var r=P(e)||0,a=parseFloat(n),o=parseFloat(e.replace(t[0],""));switch(t[0][0]){case"+":return a+o+r;case"-":return a-o+r;case"*":return a*o+r}}function A(e,n){if(f.col(e))return function(e){return f.rgb(e)?(t=/rgb\((\d+,\s*[\d]+,\s*[\d]+)\)/g.exec(n=e))?"rgba("+t[1]+",1)":n:f.hex(e)?function(e){var n=e.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i,(function(e,n,t,r){return n+n+t+t+r+r})),t=/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(n);return"rgba("+parseInt(t[1],16)+","+parseInt(t[2],16)+","+parseInt(t[3],16)+",1)"}(e):f.hsl(e)?function(e){var n,t,r,a=/hsl\((\d+),\s*([\d.]+)%,\s*([\d.]+)%\)/g.exec(e)||/hsla\((\d+),\s*([\d.]+)%,\s*([\d.]+)%,\s*([\d.]+)\)/g.exec(e),o=parseInt(a[1],10)/360,u=parseInt(a[2],10)/100,i=parseInt(a[3],10)/100,c=a[4]||1;function s(e,n,t){return t<0&&(t+=1),t>1&&(t-=1),t<1/6?e+6*(n-e)*t:t<.5?n:t<2/3?e+(n-e)*(2/3-t)*6:e}if(0==u)n=t=r=i;else{var f=i<.5?i*(1+u):i+u-i*u,l=2*i-f;n=s(l,f,o+1/3),t=s(l,f,o),r=s(l,f,o-1/3)}return"rgba("+255*n+","+255*t+","+255*r+","+c+")"}(e):void 0;var n,t}(e);if(/\s/g.test(e))return e;var t=P(e),r=t?e.substr(0,e.length-t.length):e;return n?r+n:r}function L(e,n){return Math.sqrt(Math.pow(n.x-e.x,2)+Math.pow(n.y-e.y,2))}function q(e){for(var n,t=e.points,r=0,a=0;a<t.numberOfItems;a++){var o=t.getItem(a);a>0&&(r+=L(n,o)),n=o}return r}function $(e){if(e.getTotalLength)return e.getTotalLength();switch(e.tagName.toLowerCase()){case"circle":return function(e){return 2*Math.PI*T(e,"r")}(e);case"rect":return function(e){return 2*T(e,"width")+2*T(e,"height")}(e);case"line":return function(e){return L({x:T(e,"x1"),y:T(e,"y1")},{x:T(e,"x2"),y:T(e,"y2")})}(e);case"polyline":return q(e);case"polygon":return function(e){var n=e.points;return q(e)+L(n.getItem(n.numberOfItems-1),n.getItem(0))}(e)}}function H(e,n){var t=n||{},r=t.el||function(e){for(var n=e.parentNode;f.svg(n)&&f.svg(n.parentNode);)n=n.parentNode;return n}(e),a=r.getBoundingClientRect(),o=T(r,"viewBox"),u=a.width,i=a.height,c=t.viewBox||(o?o.split(" "):[0,0,u,i]);return{el:r,viewBox:c,x:c[0]/1,y:c[1]/1,w:u,h:i,vW:c[2],vH:c[3]}}function V(e,n,t){function r(t){void 0===t&&(t=0);var r=n+t>=1?n+t:0;return e.el.getPointAtLength(r)}var a=H(e.el,e.svg),o=r(),u=r(-1),i=r(1),c=t?1:a.w/a.vW,s=t?1:a.h/a.vH;switch(e.property){case"x":return(o.x-a.x)*c;case"y":return(o.y-a.y)*s;case"angle":return 180*Math.atan2(i.y-u.y,i.x-u.x)/Math.PI}}function W(e,n){var t=/[+-]?\d*\.?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?/g,r=A(f.pth(e)?e.totalLength:e,n)+"";return{original:r,numbers:r.match(t)?r.match(t).map(Number):[0],strings:f.str(e)||n?r.split(t):[]}}function G(e){return x(e?M(f.arr(e)?e.map(w):w(e)):[],(function(e,n,t){return t.indexOf(e)===n}))}function R(e){var n=G(e);return n.map((function(e,t){return{target:e,id:t,total:n.length,transforms:{list:F(e)}}}))}function X(e,n){var t=C(n);if(/^spring/.test(t.easing)&&(t.duration=d(t.easing)),f.arr(e)){var r=e.length;2!==r||f.obj(e[0])?f.fnc(n.duration)||(t.duration=n.duration/r):e={value:e}}var a=f.arr(e)?e:[e];return a.map((function(e,t){var r=f.obj(e)&&!f.pth(e)?e:{value:e};return f.und(r.delay)&&(r.delay=t?0:n.delay),f.und(r.endDelay)&&(r.endDelay=t===a.length-1?n.endDelay:0),r})).map((function(e){return O(e,t)}))}var Y={css:function(e,n,t){return e.style[n]=t},attribute:function(e,n,t){return e.setAttribute(n,t)},object:function(e,n,t){return e[n]=t},transform:function(e,n,t,r,a){if(r.list.set(n,t),n===r.last||a){var o="";r.list.forEach((function(e,n){o+=n+"("+e+") "})),e.style.transform=o}}};function Z(e,n){R(e).forEach((function(e){for(var t in n){var r=I(n[t],e),a=e.target,o=P(r),u=j(a,t,o,e),i=E(A(r,o||P(u)),u),c=S(a,t);Y[c](a,t,i,e.transforms,!0)}}))}function _(e,n){return x(M(e.map((function(e){return n.map((function(n){return function(e,n){var t=S(e.target,n.name);if(t){var r=function(e,n){var t;return e.tweens.map((function(r){var a=function(e,n){var t={};for(var r in e){var a=I(e[r],n);f.arr(a)&&1===(a=a.map((function(e){return I(e,n)}))).length&&(a=a[0]),t[r]=a}return t.duration=parseFloat(t.duration),t.delay=parseFloat(t.delay),t}(r,n),o=a.value,u=f.arr(o)?o[1]:o,i=P(u),c=j(n.target,e.name,i,n),s=t?t.to.original:c,l=f.arr(o)?o[0]:s,d=P(l)||P(c),p=i||d;return f.und(u)&&(u=s),a.from=W(l,p),a.to=W(E(u,l),p),a.start=t?t.end:0,a.end=a.start+a.delay+a.duration+a.endDelay,a.easing=y(a.easing,a.duration),a.isPath=f.pth(o),a.isPathTargetInsideSVG=a.isPath&&f.svg(n.target),a.isColor=f.col(a.from.original),a.isColor&&(a.round=1),t=a,a}))}(n,e),a=r[r.length-1];return{type:t,property:n.name,animatable:e,tweens:r,duration:a.end,delay:r[0].delay,endDelay:a.endDelay}}}(e,n)}))}))),(function(e){return!f.und(e)}))}function Q(e,n){var t=e.length,r=function(e){return e.timelineOffset?e.timelineOffset:0},a={};return a.duration=t?Math.max.apply(Math,e.map((function(e){return r(e)+e.duration}))):n.duration,a.delay=t?Math.min.apply(Math,e.map((function(e){return r(e)+e.delay}))):n.delay,a.endDelay=t?a.duration-Math.max.apply(Math,e.map((function(e){return r(e)+e.duration-e.endDelay}))):n.endDelay,a}var z=0,J=[],K=function(){var e;function n(t){for(var r=J.length,a=0;a<r;){var o=J[a];o.paused?(J.splice(a,1),r--):(o.tick(t),a++)}e=a>0?requestAnimationFrame(n):void 0}return"undefined"!=typeof document&&document.addEventListener("visibilitychange",(function(){ee.suspendWhenDocumentHidden&&(U()?e=cancelAnimationFrame(e):(J.forEach((function(e){return e._onDocumentVisibility()})),K()))})),function(){e||U()&&ee.suspendWhenDocumentHidden||!(J.length>0)||(e=requestAnimationFrame(n))}}();function U(){return!!document&&document.hidden}function ee(e){void 0===e&&(e={});var r,a=0,o=0,u=0,c=0,s=null;function l(e){var n=window.Promise&&new Promise((function(e){return s=e}));return e.finished=n,n}var d=function(e){var r=D(n,e),a=D(t,e),o=function(e,n){var t=[],r=n.keyframes;for(var a in r&&(n=O(function(e){for(var n=x(M(e.map((function(e){return Object.keys(e)}))),(function(e){return f.key(e)})).reduce((function(e,n){return e.indexOf(n)<0&&e.push(n),e}),[]),t={},r=function(r){var a=n[r];t[a]=e.map((function(e){var n={};for(var t in e)f.key(t)?t==a&&(n.value=e[t]):n[t]=e[t];return n}))},a=0;a<n.length;a++)r(a);return t}(r),n)),n)f.key(a)&&t.push({name:a,tweens:X(n[a],e)});return t}(a,e),u=R(e.targets),i=_(u,o),c=Q(i,a),s=z;return z++,O(r,{id:s,children:[],animatables:u,animations:i,duration:c.duration,delay:c.delay,endDelay:c.endDelay})}(e);function p(){var e=d.direction;"alternate"!==e&&(d.direction="normal"!==e?"normal":"reverse"),d.reversed=!d.reversed,r.forEach((function(e){return e.reversed=d.reversed}))}function v(e){return d.reversed?d.duration-e:e}function m(){a=0,o=v(d.currentTime)*(1/ee.speed)}function h(e,n){n&&n.seek(e-n.timelineOffset)}function g(e){for(var n=0,t=d.animations,r=t.length;n<r;){var a=t[n],o=a.animatable,u=a.tweens,c=u.length-1,s=u[c];c&&(s=x(u,(function(n){return e<n.end}))[0]||s);for(var f=i(e-s.start-s.delay,0,s.duration)/s.duration,l=isNaN(f)?1:s.easing(f),p=s.to.strings,v=s.round,m=[],h=s.to.numbers.length,g=void 0,y=0;y<h;y++){var b=void 0,M=s.to.numbers[y],w=s.from.numbers[y]||0;b=s.isPath?V(s.value,l*M,s.isPathTargetInsideSVG):w+l*(M-w),v&&(s.isColor&&y>2||(b=Math.round(b*v)/v)),m.push(b)}var k=p.length;if(k){g=p[0];for(var C=0;C<k;C++){p[C];var D=p[C+1],O=m[C];isNaN(O)||(g+=D?O+D:O+" ")}}else g=m[0];Y[a.type](o.target,a.property,g,o.transforms),a.currentValue=g,n++}}function y(e){d[e]&&!d.passThrough&&d[e](d)}function b(e){var n=d.duration,t=d.delay,f=n-d.endDelay,m=v(e);d.progress=i(m/n*100,0,100),d.reversePlayback=m<d.currentTime,r&&function(e){if(d.reversePlayback)for(var n=c;n--;)h(e,r[n]);else for(var t=0;t<c;t++)h(e,r[t])}(m),!d.began&&d.currentTime>0&&(d.began=!0,y("begin")),!d.loopBegan&&d.currentTime>0&&(d.loopBegan=!0,y("loopBegin")),m<=t&&0!==d.currentTime&&g(0),(m>=f&&d.currentTime!==n||!n)&&g(n),m>t&&m<f?(d.changeBegan||(d.changeBegan=!0,d.changeCompleted=!1,y("changeBegin")),y("change"),g(m)):d.changeBegan&&(d.changeCompleted=!0,d.changeBegan=!1,y("changeComplete")),d.currentTime=i(m,0,n),d.began&&y("update"),e>=n&&(o=0,d.remaining&&!0!==d.remaining&&d.remaining--,d.remaining?(a=u,y("loopComplete"),d.loopBegan=!1,"alternate"===d.direction&&p()):(d.paused=!0,d.completed||(d.completed=!0,y("loopComplete"),y("complete"),!d.passThrough&&"Promise"in window&&(s(),l(d)))))}return l(d),d.reset=function(){var e=d.direction;d.passThrough=!1,d.currentTime=0,d.progress=0,d.paused=!0,d.began=!1,d.loopBegan=!1,d.changeBegan=!1,d.completed=!1,d.changeCompleted=!1,d.reversePlayback=!1,d.reversed="reverse"===e,d.remaining=d.loop,r=d.children;for(var n=c=r.length;n--;)d.children[n].reset();(d.reversed&&!0!==d.loop||"alternate"===e&&1===d.loop)&&d.remaining++,g(d.reversed?d.duration:0)},d._onDocumentVisibility=m,d.set=function(e,n){return Z(e,n),d},d.tick=function(e){u=e,a||(a=u),b((u+(o-a))*ee.speed)},d.seek=function(e){b(v(e))},d.pause=function(){d.paused=!0,m()},d.play=function(){d.paused&&(d.completed&&d.reset(),d.paused=!1,J.push(d),m(),K())},d.reverse=function(){p(),d.completed=!d.reversed,m()},d.restart=function(){d.reset(),d.play()},d.remove=function(e){te(G(e),d)},d.reset(),d.autoplay&&d.play(),d}function ne(e,n){for(var t=n.length;t--;)k(e,n[t].animatable.target)&&n.splice(t,1)}function te(e,n){var t=n.animations,r=n.children;ne(e,t);for(var a=r.length;a--;){var o=r[a],u=o.animations;ne(e,u),u.length||o.children.length||r.splice(a,1)}t.length||r.length||n.pause()}ee.version="3.2.1",ee.speed=1,ee.suspendWhenDocumentHidden=!0,ee.running=J,ee.remove=function(e){for(var n=G(e),t=J.length;t--;)te(n,J[t])},ee.get=j,ee.set=Z,ee.convertPx=N,ee.path=function(e,n){var t=f.str(e)?b(e)[0]:e,r=n||100;return function(e){return{property:e,el:t,svg:H(t),totalLength:$(t)*(r/100)}}},ee.setDashoffset=function(e){var n=$(e);return e.setAttribute("stroke-dasharray",n),n},ee.stagger=function(e,n){void 0===n&&(n={});var t=n.direction||"normal",r=n.easing?y(n.easing):null,a=n.grid,o=n.axis,u=n.from||0,i="first"===u,c="center"===u,s="last"===u,l=f.arr(e),d=l?parseFloat(e[0]):parseFloat(e),p=l?parseFloat(e[1]):0,v=P(l?e[1]:e)||0,m=n.start||0+(l?d:0),h=[],g=0;return function(e,n,f){if(i&&(u=0),c&&(u=(f-1)/2),s&&(u=f-1),!h.length){for(var y=0;y<f;y++){if(a){var b=c?(a[0]-1)/2:u%a[0],x=c?(a[1]-1)/2:Math.floor(u/a[0]),M=b-y%a[0],w=x-Math.floor(y/a[0]),k=Math.sqrt(M*M+w*w);"x"===o&&(k=-M),"y"===o&&(k=-w),h.push(k)}else h.push(Math.abs(u-y));g=Math.max.apply(Math,h)}r&&(h=h.map((function(e){return r(e/g)*g}))),"reverse"===t&&(h=h.map((function(e){return o?e<0?-1*e:-e:Math.abs(g-e)})))}return m+(l?(p-d)/g:d)*(Math.round(100*h[n])/100)+v}},ee.timeline=function(e){void 0===e&&(e={});var n=ee(e);return n.duration=0,n.add=function(r,a){var o=J.indexOf(n),u=n.children;function i(e){e.passThrough=!0}o>-1&&J.splice(o,1);for(var c=0;c<u.length;c++)i(u[c]);var s=O(r,D(t,e));s.targets=s.targets||e.targets;var l=n.duration;s.autoplay=!1,s.direction=n.direction,s.timelineOffset=f.und(a)?l:E(a,l),i(n),n.seek(s.timelineOffset);var d=ee(s);i(d),u.push(d);var p=Q(u,e);return n.delay=p.delay,n.endDelay=p.endDelay,n.duration=p.duration,n.seek(0),n.reset(),n.autoplay&&n.play(),n},n},ee.easing=y,ee.penner=g,ee.random=function(e,n){return Math.floor(Math.random()*(n-e+1))+e};const re=ee,ae=e=>{const n=e.toString().match(/\.\d+/);return n?n[0].length-1:0},oe=(0,e.forwardRef)(((e,n)=>{const{className:t,prefix:r,suffix:a,align:o="center",children:u}=e;return Depicter.h("div",{className:`${t} dpc-counter dpc-placement-${o}`},r&&Depicter.h("span",{className:"dpc-counter-prefix"},r),Depicter.h("span",{className:"dpc-counter-number",ref:n},u),a&&Depicter.h("span",{className:"dpc-counter-suffix"},a))})),ue={component:(0,e.forwardRef)(((n,t)=>{const{className:r="dpc-counter",initialValue:a=0,targetValue:o=0,duration:u=2e3,separator:i,prefix:c,suffix:s,useGroup:f,easing:l="linear",align:d="center",delay:p=1,onAnimationInit:v}=n,m=(0,e.useRef)(null),h=Math.max(ae(a),ae(o),0),g=(0,e.useRef)(null),y=(0,e.useCallback)((e=>{const n=m.current,t=e.animatables[0].target.value,r=parseFloat(t.toFixed(h)),a=f&&i?function(e){let n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:",";return e.toLocaleString(void 0,{useGrouping:!0,minimumFractionDigits:0,maximumFractionDigits:3}).replace(/,/g,n)}(r,i):`${r}`;n&&(n.innerHTML=`${a}`)}),[h,i,f]);(0,e.useEffect)((()=>(m.current&&(g.current=re({targets:{value:a},value:o,easing:l,duration:u,round:!1,delay:p,autoplay:!1,update:y}),v?.()),()=>{g.current&&re.remove(g.current)})),[p,u,l,a,v,o,y]);const b=(0,e.useCallback)((()=>{g.current?.play()}),[]),x=(0,e.useCallback)((()=>{g.current?.pause()}),[]),M=(0,e.useCallback)((()=>{g.current?.seek(0),g.current?.pause()}),[]);return(0,e.useImperativeHandle)(t,(()=>({play:b,pause:x,stop:M}))),Depicter.h(oe,{className:r,suffix:s,prefix:c,align:d,ref:m})})),async:!1}})(),a})()));