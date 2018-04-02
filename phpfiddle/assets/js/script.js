/*! jQuery v2.0.3 | (c) 2005, 2013 jQuery Foundation, Inc. | jquery.org/license */
(function (e, undefined) {
    var t, n, r = typeof undefined, i = e.location, o = e.document, s = o.documentElement, a = e.jQuery, u = e.$, l = {}, c = [], p = "2.0.3", f = c.concat, h = c.push, d = c.slice, g = c.indexOf, m = l.toString, y = l.hasOwnProperty, v = p.trim, x = function (e, n) {
        return new x.fn.init(e, n, t)
    }, b = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source, w = /\S+/g, T = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/, C = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, k = /^-ms-/, N = /-([\da-z])/gi, E = function (e, t) {
        return t.toUpperCase()
    }, S = function () {
        o.removeEventListener("DOMContentLoaded", S, !1), e.removeEventListener("load", S, !1), x.ready()
    };
    x.fn = x.prototype = {
        jquery: p, constructor: x, init: function (e, t, n) {
            var r, i;
            if (!e)return this;
            if ("string" == typeof e) {
                if (r = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && e.length >= 3 ? [null, e, null] : T.exec(e), !r || !r[1] && t)return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
                if (r[1]) {
                    if (t = t instanceof x ? t[0] : t, x.merge(this, x.parseHTML(r[1], t && t.nodeType ? t.ownerDocument || t : o, !0)), C.test(r[1]) && x.isPlainObject(t))for (r in t)x.isFunction(this[r]) ? this[r](t[r]) : this.attr(r, t[r]);
                    return this
                }
                return i = o.getElementById(r[2]), i && i.parentNode && (this.length = 1, this[0] = i), this.context = o, this.selector = e, this
            }
            return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : x.isFunction(e) ? n.ready(e) : (e.selector !== undefined && (this.selector = e.selector, this.context = e.context), x.makeArray(e, this))
        }, selector: "", length: 0, toArray: function () {
            return d.call(this)
        }, get: function (e) {
            return null == e ? this.toArray() : 0 > e ? this[this.length + e] : this[e]
        }, pushStack: function (e) {
            var t = x.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        }, each: function (e, t) {
            return x.each(this, e, t)
        }, ready: function (e) {
            return x.ready.promise().done(e), this
        }, slice: function () {
            return this.pushStack(d.apply(this, arguments))
        }, first: function () {
            return this.eq(0)
        }, last: function () {
            return this.eq(-1)
        }, eq: function (e) {
            var t = this.length, n = +e + (0 > e ? t : 0);
            return this.pushStack(n >= 0 && t > n ? [this[n]] : [])
        }, map: function (e) {
            return this.pushStack(x.map(this, function (t, n) {
                return e.call(t, n, t)
            }))
        }, end: function () {
            return this.prevObject || this.constructor(null)
        }, push: h, sort: [].sort, splice: [].splice
    }, x.fn.init.prototype = x.fn, x.extend = x.fn.extend = function () {
        var e, t, n, r, i, o, s = arguments[0] || {}, a = 1, u = arguments.length, l = !1;
        for ("boolean" == typeof s && (l = s, s = arguments[1] || {}, a = 2), "object" == typeof s || x.isFunction(s) || (s = {}), u === a && (s = this, --a); u > a; a++)if (null != (e = arguments[a]))for (t in e)n = s[t], r = e[t], s !== r && (l && r && (x.isPlainObject(r) || (i = x.isArray(r))) ? (i ? (i = !1, o = n && x.isArray(n) ? n : []) : o = n && x.isPlainObject(n) ? n : {}, s[t] = x.extend(l, o, r)) : r !== undefined && (s[t] = r));
        return s
    }, x.extend({
        expando: "jQuery" + (p + Math.random()).replace(/\D/g, ""), noConflict: function (t) {
            return e.$ === x && (e.$ = u), t && e.jQuery === x && (e.jQuery = a), x
        }, isReady: !1, readyWait: 1, holdReady: function (e) {
            e ? x.readyWait++ : x.ready(!0)
        }, ready: function (e) {
            (e === !0 ? --x.readyWait : x.isReady) || (x.isReady = !0, e !== !0 && --x.readyWait > 0 || (n.resolveWith(o, [x]), x.fn.trigger && x(o).trigger("ready").off("ready")))
        }, isFunction: function (e) {
            return "function" === x.type(e)
        }, isArray: Array.isArray, isWindow: function (e) {
            return null != e && e === e.window
        }, isNumeric: function (e) {
            return !isNaN(parseFloat(e)) && isFinite(e)
        }, type: function (e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? l[m.call(e)] || "object" : typeof e
        }, isPlainObject: function (e) {
            if ("object" !== x.type(e) || e.nodeType || x.isWindow(e))return !1;
            try {
                if (e.constructor && !y.call(e.constructor.prototype, "isPrototypeOf"))return !1
            } catch (t) {
                return !1
            }
            return !0
        }, isEmptyObject: function (e) {
            var t;
            for (t in e)return !1;
            return !0
        }, error: function (e) {
            throw Error(e)
        }, parseHTML: function (e, t, n) {
            if (!e || "string" != typeof e)return null;
            "boolean" == typeof t && (n = t, t = !1), t = t || o;
            var r = C.exec(e), i = !n && [];
            return r ? [t.createElement(r[1])] : (r = x.buildFragment([e], t, i), i && x(i).remove(), x.merge([], r.childNodes))
        }, parseJSON: JSON.parse, parseXML: function (e) {
            var t, n;
            if (!e || "string" != typeof e)return null;
            try {
                n = new DOMParser, t = n.parseFromString(e, "text/xml")
            } catch (r) {
                t = undefined
            }
            return (!t || t.getElementsByTagName("parsererror").length) && x.error("Invalid XML: " + e), t
        }, noop: function () {
        }, globalEval: function (e) {
            var t, n = eval;
            e = x.trim(e), e && (1 === e.indexOf("use strict") ? (t = o.createElement("script"), t.text = e, o.head.appendChild(t).parentNode.removeChild(t)) : n(e))
        }, camelCase: function (e) {
            return e.replace(k, "ms-").replace(N, E)
        }, nodeName: function (e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        }, each: function (e, t, n) {
            var r, i = 0, o = e.length, s = j(e);
            if (n) {
                if (s) {
                    for (; o > i; i++)if (r = t.apply(e[i], n), r === !1)break
                } else for (i in e)if (r = t.apply(e[i], n), r === !1)break
            } else if (s) {
                for (; o > i; i++)if (r = t.call(e[i], i, e[i]), r === !1)break
            } else for (i in e)if (r = t.call(e[i], i, e[i]), r === !1)break;
            return e
        }, trim: function (e) {
            return null == e ? "" : v.call(e)
        }, makeArray: function (e, t) {
            var n = t || [];
            return null != e && (j(Object(e)) ? x.merge(n, "string" == typeof e ? [e] : e) : h.call(n, e)), n
        }, inArray: function (e, t, n) {
            return null == t ? -1 : g.call(t, e, n)
        }, merge: function (e, t) {
            var n = t.length, r = e.length, i = 0;
            if ("number" == typeof n)for (; n > i; i++)e[r++] = t[i]; else while (t[i] !== undefined)e[r++] = t[i++];
            return e.length = r, e
        }, grep: function (e, t, n) {
            var r, i = [], o = 0, s = e.length;
            for (n = !!n; s > o; o++)r = !!t(e[o], o), n !== r && i.push(e[o]);
            return i
        }, map: function (e, t, n) {
            var r, i = 0, o = e.length, s = j(e), a = [];
            if (s)for (; o > i; i++)r = t(e[i], i, n), null != r && (a[a.length] = r); else for (i in e)r = t(e[i], i, n), null != r && (a[a.length] = r);
            return f.apply([], a)
        }, guid: 1, proxy: function (e, t) {
            var n, r, i;
            return "string" == typeof t && (n = e[t], t = e, e = n), x.isFunction(e) ? (r = d.call(arguments, 2), i = function () {
                return e.apply(t || this, r.concat(d.call(arguments)))
            }, i.guid = e.guid = e.guid || x.guid++, i) : undefined
        }, access: function (e, t, n, r, i, o, s) {
            var a = 0, u = e.length, l = null == n;
            if ("object" === x.type(n)) {
                i = !0;
                for (a in n)x.access(e, t, a, n[a], !0, o, s)
            } else if (r !== undefined && (i = !0, x.isFunction(r) || (s = !0), l && (s ? (t.call(e, r), t = null) : (l = t, t = function (e, t, n) {
                    return l.call(x(e), n)
                })), t))for (; u > a; a++)t(e[a], n, s ? r : r.call(e[a], a, t(e[a], n)));
            return i ? e : l ? t.call(e) : u ? t(e[0], n) : o
        }, now: Date.now, swap: function (e, t, n, r) {
            var i, o, s = {};
            for (o in t)s[o] = e.style[o], e.style[o] = t[o];
            i = n.apply(e, r || []);
            for (o in t)e.style[o] = s[o];
            return i
        }
    }), x.ready.promise = function (t) {
        return n || (n = x.Deferred(), "complete" === o.readyState ? setTimeout(x.ready) : (o.addEventListener("DOMContentLoaded", S, !1), e.addEventListener("load", S, !1))), n.promise(t)
    }, x.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function (e, t) {
        l["[object " + t + "]"] = t.toLowerCase()
    });
    function j(e) {
        var t = e.length, n = x.type(e);
        return x.isWindow(e) ? !1 : 1 === e.nodeType && t ? !0 : "array" === n || "function" !== n && (0 === t || "number" == typeof t && t > 0 && t - 1 in e)
    }

    t = x(o), function (e, undefined) {
        var t, n, r, i, o, s, a, u, l, c, p, f, h, d, g, m, y, v = "sizzle" + -new Date, b = e.document, w = 0, T = 0, C = st(), k = st(), N = st(), E = !1, S = function (e, t) {
            return e === t ? (E = !0, 0) : 0
        }, j = typeof undefined, D = 1 << 31, A = {}.hasOwnProperty, L = [], q = L.pop, H = L.push, O = L.push, F = L.slice, P = L.indexOf || function (e) {
                var t = 0, n = this.length;
                for (; n > t; t++)if (this[t] === e)return t;
                return -1
            }, R = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", M = "[\\x20\\t\\r\\n\\f]", W = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+", $ = W.replace("w", "w#"), B = "\\[" + M + "*(" + W + ")" + M + "*(?:([*^$|!~]?=)" + M + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + $ + ")|)|)" + M + "*\\]", I = ":(" + W + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + B.replace(3, 8) + ")*)|.*)\\)|)", z = RegExp("^" + M + "+|((?:^|[^\\\\])(?:\\\\.)*)" + M + "+$", "g"), _ = RegExp("^" + M + "*," + M + "*"), X = RegExp("^" + M + "*([>+~]|" + M + ")" + M + "*"), U = RegExp(M + "*[+~]"), Y = RegExp("=" + M + "*([^\\]'\"]*)" + M + "*\\]", "g"), V = RegExp(I), G = RegExp("^" + $ + "$"), J = {
            ID: RegExp("^#(" + W + ")"),
            CLASS: RegExp("^\\.(" + W + ")"),
            TAG: RegExp("^(" + W.replace("w", "w*") + ")"),
            ATTR: RegExp("^" + B),
            PSEUDO: RegExp("^" + I),
            CHILD: RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + M + "*(even|odd|(([+-]|)(\\d*)n|)" + M + "*(?:([+-]|)" + M + "*(\\d+)|))" + M + "*\\)|)", "i"),
            bool: RegExp("^(?:" + R + ")$", "i"),
            needsContext: RegExp("^" + M + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + M + "*((?:-\\d)?\\d*)" + M + "*\\)|)(?=[^-]|$)", "i")
        }, Q = /^[^{]+\{\s*\[native \w/, K = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, Z = /^(?:input|select|textarea|button)$/i, et = /^h\d$/i, tt = /'|\\/g, nt = RegExp("\\\\([\\da-f]{1,6}" + M + "?|(" + M + ")|.)", "ig"), rt = function (e, t, n) {
            var r = "0x" + t - 65536;
            return r !== r || n ? t : 0 > r ? String.fromCharCode(r + 65536) : String.fromCharCode(55296 | r >> 10, 56320 | 1023 & r)
        };
        try {
            O.apply(L = F.call(b.childNodes), b.childNodes), L[b.childNodes.length].nodeType
        } catch (it) {
            O = {
                apply: L.length ? function (e, t) {
                    H.apply(e, F.call(t))
                } : function (e, t) {
                    var n = e.length, r = 0;
                    while (e[n++] = t[r++]);
                    e.length = n - 1
                }
            }
        }
        function ot(e, t, r, i) {
            var o, s, a, u, l, f, g, m, x, w;
            if ((t ? t.ownerDocument || t : b) !== p && c(t), t = t || p, r = r || [], !e || "string" != typeof e)return r;
            if (1 !== (u = t.nodeType) && 9 !== u)return [];
            if (h && !i) {
                if (o = K.exec(e))if (a = o[1]) {
                    if (9 === u) {
                        if (s = t.getElementById(a), !s || !s.parentNode)return r;
                        if (s.id === a)return r.push(s), r
                    } else if (t.ownerDocument && (s = t.ownerDocument.getElementById(a)) && y(t, s) && s.id === a)return r.push(s), r
                } else {
                    if (o[2])return O.apply(r, t.getElementsByTagName(e)), r;
                    if ((a = o[3]) && n.getElementsByClassName && t.getElementsByClassName)return O.apply(r, t.getElementsByClassName(a)), r
                }
                if (n.qsa && (!d || !d.test(e))) {
                    if (m = g = v, x = t, w = 9 === u && e, 1 === u && "object" !== t.nodeName.toLowerCase()) {
                        f = gt(e), (g = t.getAttribute("id")) ? m = g.replace(tt, "\\$&") : t.setAttribute("id", m), m = "[id='" + m + "'] ", l = f.length;
                        while (l--)f[l] = m + mt(f[l]);
                        x = U.test(e) && t.parentNode || t, w = f.join(",")
                    }
                    if (w)try {
                        return O.apply(r, x.querySelectorAll(w)), r
                    } catch (T) {
                    } finally {
                        g || t.removeAttribute("id")
                    }
                }
            }
            return kt(e.replace(z, "$1"), t, r, i)
        }

        function st() {
            var e = [];

            function t(n, r) {
                return e.push(n += " ") > i.cacheLength && delete t[e.shift()], t[n] = r
            }

            return t
        }

        function at(e) {
            return e[v] = !0, e
        }

        function ut(e) {
            var t = p.createElement("div");
            try {
                return !!e(t)
            } catch (n) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function lt(e, t) {
            var n = e.split("|"), r = e.length;
            while (r--)i.attrHandle[n[r]] = t
        }

        function ct(e, t) {
            var n = t && e, r = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || D) - (~e.sourceIndex || D);
            if (r)return r;
            if (n)while (n = n.nextSibling)if (n === t)return -1;
            return e ? 1 : -1
        }

        function pt(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return "input" === n && t.type === e
            }
        }

        function ft(e) {
            return function (t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function ht(e) {
            return at(function (t) {
                return t = +t, at(function (n, r) {
                    var i, o = e([], n.length, t), s = o.length;
                    while (s--)n[i = o[s]] && (n[i] = !(r[i] = n[i]))
                })
            })
        }

        s = ot.isXML = function (e) {
            var t = e && (e.ownerDocument || e).documentElement;
            return t ? "HTML" !== t.nodeName : !1
        }, n = ot.support = {}, c = ot.setDocument = function (e) {
            var t = e ? e.ownerDocument || e : b, r = t.defaultView;
            return t !== p && 9 === t.nodeType && t.documentElement ? (p = t, f = t.documentElement, h = !s(t), r && r.attachEvent && r !== r.top && r.attachEvent("onbeforeunload", function () {
                c()
            }), n.attributes = ut(function (e) {
                return e.className = "i", !e.getAttribute("className")
            }), n.getElementsByTagName = ut(function (e) {
                return e.appendChild(t.createComment("")), !e.getElementsByTagName("*").length
            }), n.getElementsByClassName = ut(function (e) {
                return e.innerHTML = "<div class='a'></div><div class='a i'></div>", e.firstChild.className = "i", 2 === e.getElementsByClassName("i").length
            }), n.getById = ut(function (e) {
                return f.appendChild(e).id = v, !t.getElementsByName || !t.getElementsByName(v).length
            }), n.getById ? (i.find.ID = function (e, t) {
                if (typeof t.getElementById !== j && h) {
                    var n = t.getElementById(e);
                    return n && n.parentNode ? [n] : []
                }
            }, i.filter.ID = function (e) {
                var t = e.replace(nt, rt);
                return function (e) {
                    return e.getAttribute("id") === t
                }
            }) : (delete i.find.ID, i.filter.ID = function (e) {
                var t = e.replace(nt, rt);
                return function (e) {
                    var n = typeof e.getAttributeNode !== j && e.getAttributeNode("id");
                    return n && n.value === t
                }
            }), i.find.TAG = n.getElementsByTagName ? function (e, t) {
                return typeof t.getElementsByTagName !== j ? t.getElementsByTagName(e) : undefined
            } : function (e, t) {
                var n, r = [], i = 0, o = t.getElementsByTagName(e);
                if ("*" === e) {
                    while (n = o[i++])1 === n.nodeType && r.push(n);
                    return r
                }
                return o
            }, i.find.CLASS = n.getElementsByClassName && function (e, t) {
                return typeof t.getElementsByClassName !== j && h ? t.getElementsByClassName(e) : undefined
            }, g = [], d = [], (n.qsa = Q.test(t.querySelectorAll)) && (ut(function (e) {
                e.innerHTML = "<select><option selected=''></option></select>", e.querySelectorAll("[selected]").length || d.push("\\[" + M + "*(?:value|" + R + ")"), e.querySelectorAll(":checked").length || d.push(":checked")
            }), ut(function (e) {
                var n = t.createElement("input");
                n.setAttribute("type", "hidden"), e.appendChild(n).setAttribute("t", ""), e.querySelectorAll("[t^='']").length && d.push("[*^$]=" + M + "*(?:''|\"\")"), e.querySelectorAll(":enabled").length || d.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), d.push(",.*:")
            })), (n.matchesSelector = Q.test(m = f.webkitMatchesSelector || f.mozMatchesSelector || f.oMatchesSelector || f.msMatchesSelector)) && ut(function (e) {
                n.disconnectedMatch = m.call(e, "div"), m.call(e, "[s!='']:x"), g.push("!=", I)
            }), d = d.length && RegExp(d.join("|")), g = g.length && RegExp(g.join("|")), y = Q.test(f.contains) || f.compareDocumentPosition ? function (e, t) {
                var n = 9 === e.nodeType ? e.documentElement : e, r = t && t.parentNode;
                return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)))
            } : function (e, t) {
                if (t)while (t = t.parentNode)if (t === e)return !0;
                return !1
            }, S = f.compareDocumentPosition ? function (e, r) {
                if (e === r)return E = !0, 0;
                var i = r.compareDocumentPosition && e.compareDocumentPosition && e.compareDocumentPosition(r);
                return i ? 1 & i || !n.sortDetached && r.compareDocumentPosition(e) === i ? e === t || y(b, e) ? -1 : r === t || y(b, r) ? 1 : l ? P.call(l, e) - P.call(l, r) : 0 : 4 & i ? -1 : 1 : e.compareDocumentPosition ? -1 : 1
            } : function (e, n) {
                var r, i = 0, o = e.parentNode, s = n.parentNode, a = [e], u = [n];
                if (e === n)return E = !0, 0;
                if (!o || !s)return e === t ? -1 : n === t ? 1 : o ? -1 : s ? 1 : l ? P.call(l, e) - P.call(l, n) : 0;
                if (o === s)return ct(e, n);
                r = e;
                while (r = r.parentNode)a.unshift(r);
                r = n;
                while (r = r.parentNode)u.unshift(r);
                while (a[i] === u[i])i++;
                return i ? ct(a[i], u[i]) : a[i] === b ? -1 : u[i] === b ? 1 : 0
            }, t) : p
        }, ot.matches = function (e, t) {
            return ot(e, null, null, t)
        }, ot.matchesSelector = function (e, t) {
            if ((e.ownerDocument || e) !== p && c(e), t = t.replace(Y, "='$1']"), !(!n.matchesSelector || !h || g && g.test(t) || d && d.test(t)))try {
                var r = m.call(e, t);
                if (r || n.disconnectedMatch || e.document && 11 !== e.document.nodeType)return r
            } catch (i) {
            }
            return ot(t, p, null, [e]).length > 0
        }, ot.contains = function (e, t) {
            return (e.ownerDocument || e) !== p && c(e), y(e, t)
        }, ot.attr = function (e, t) {
            (e.ownerDocument || e) !== p && c(e);
            var r = i.attrHandle[t.toLowerCase()], o = r && A.call(i.attrHandle, t.toLowerCase()) ? r(e, t, !h) : undefined;
            return o === undefined ? n.attributes || !h ? e.getAttribute(t) : (o = e.getAttributeNode(t)) && o.specified ? o.value : null : o
        }, ot.error = function (e) {
            throw Error("Syntax error, unrecognized expression: " + e)
        }, ot.uniqueSort = function (e) {
            var t, r = [], i = 0, o = 0;
            if (E = !n.detectDuplicates, l = !n.sortStable && e.slice(0), e.sort(S), E) {
                while (t = e[o++])t === e[o] && (i = r.push(o));
                while (i--)e.splice(r[i], 1)
            }
            return e
        }, o = ot.getText = function (e) {
            var t, n = "", r = 0, i = e.nodeType;
            if (i) {
                if (1 === i || 9 === i || 11 === i) {
                    if ("string" == typeof e.textContent)return e.textContent;
                    for (e = e.firstChild; e; e = e.nextSibling)n += o(e)
                } else if (3 === i || 4 === i)return e.nodeValue
            } else for (; t = e[r]; r++)n += o(t);
            return n
        }, i = ot.selectors = {
            cacheLength: 50,
            createPseudo: at,
            match: J,
            attrHandle: {},
            find: {},
            relative: {
                ">": {dir: "parentNode", first: !0},
                " ": {dir: "parentNode"},
                "+": {dir: "previousSibling", first: !0},
                "~": {dir: "previousSibling"}
            },
            preFilter: {
                ATTR: function (e) {
                    return e[1] = e[1].replace(nt, rt), e[3] = (e[4] || e[5] || "").replace(nt, rt), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                }, CHILD: function (e) {
                    return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || ot.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && ot.error(e[0]), e
                }, PSEUDO: function (e) {
                    var t, n = !e[5] && e[2];
                    return J.CHILD.test(e[0]) ? null : (e[3] && e[4] !== undefined ? e[2] = e[4] : n && V.test(n) && (t = gt(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                }
            },
            filter: {
                TAG: function (e) {
                    var t = e.replace(nt, rt).toLowerCase();
                    return "*" === e ? function () {
                        return !0
                    } : function (e) {
                        return e.nodeName && e.nodeName.toLowerCase() === t
                    }
                }, CLASS: function (e) {
                    var t = C[e + " "];
                    return t || (t = RegExp("(^|" + M + ")" + e + "(" + M + "|$)")) && C(e, function (e) {
                            return t.test("string" == typeof e.className && e.className || typeof e.getAttribute !== j && e.getAttribute("class") || "")
                        })
                }, ATTR: function (e, t, n) {
                    return function (r) {
                        var i = ot.attr(r, e);
                        return null == i ? "!=" === t : t ? (i += "", "=" === t ? i === n : "!=" === t ? i !== n : "^=" === t ? n && 0 === i.indexOf(n) : "*=" === t ? n && i.indexOf(n) > -1 : "$=" === t ? n && i.slice(-n.length) === n : "~=" === t ? (" " + i + " ").indexOf(n) > -1 : "|=" === t ? i === n || i.slice(0, n.length + 1) === n + "-" : !1) : !0
                    }
                }, CHILD: function (e, t, n, r, i) {
                    var o = "nth" !== e.slice(0, 3), s = "last" !== e.slice(-4), a = "of-type" === t;
                    return 1 === r && 0 === i ? function (e) {
                        return !!e.parentNode
                    } : function (t, n, u) {
                        var l, c, p, f, h, d, g = o !== s ? "nextSibling" : "previousSibling", m = t.parentNode, y = a && t.nodeName.toLowerCase(), x = !u && !a;
                        if (m) {
                            if (o) {
                                while (g) {
                                    p = t;
                                    while (p = p[g])if (a ? p.nodeName.toLowerCase() === y : 1 === p.nodeType)return !1;
                                    d = g = "only" === e && !d && "nextSibling"
                                }
                                return !0
                            }
                            if (d = [s ? m.firstChild : m.lastChild], s && x) {
                                c = m[v] || (m[v] = {}), l = c[e] || [], h = l[0] === w && l[1], f = l[0] === w && l[2], p = h && m.childNodes[h];
                                while (p = ++h && p && p[g] || (f = h = 0) || d.pop())if (1 === p.nodeType && ++f && p === t) {
                                    c[e] = [w, h, f];
                                    break
                                }
                            } else if (x && (l = (t[v] || (t[v] = {}))[e]) && l[0] === w)f = l[1]; else while (p = ++h && p && p[g] || (f = h = 0) || d.pop())if ((a ? p.nodeName.toLowerCase() === y : 1 === p.nodeType) && ++f && (x && ((p[v] || (p[v] = {}))[e] = [w, f]), p === t))break;
                            return f -= i, f === r || 0 === f % r && f / r >= 0
                        }
                    }
                }, PSEUDO: function (e, t) {
                    var n, r = i.pseudos[e] || i.setFilters[e.toLowerCase()] || ot.error("unsupported pseudo: " + e);
                    return r[v] ? r(t) : r.length > 1 ? (n = [e, e, "", t], i.setFilters.hasOwnProperty(e.toLowerCase()) ? at(function (e, n) {
                        var i, o = r(e, t), s = o.length;
                        while (s--)i = P.call(e, o[s]), e[i] = !(n[i] = o[s])
                    }) : function (e) {
                        return r(e, 0, n)
                    }) : r
                }
            },
            pseudos: {
                not: at(function (e) {
                    var t = [], n = [], r = a(e.replace(z, "$1"));
                    return r[v] ? at(function (e, t, n, i) {
                        var o, s = r(e, null, i, []), a = e.length;
                        while (a--)(o = s[a]) && (e[a] = !(t[a] = o))
                    }) : function (e, i, o) {
                        return t[0] = e, r(t, null, o, n), !n.pop()
                    }
                }), has: at(function (e) {
                    return function (t) {
                        return ot(e, t).length > 0
                    }
                }), contains: at(function (e) {
                    return function (t) {
                        return (t.textContent || t.innerText || o(t)).indexOf(e) > -1
                    }
                }), lang: at(function (e) {
                    return G.test(e || "") || ot.error("unsupported lang: " + e), e = e.replace(nt, rt).toLowerCase(), function (t) {
                        var n;
                        do if (n = h ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))return n = n.toLowerCase(), n === e || 0 === n.indexOf(e + "-"); while ((t = t.parentNode) && 1 === t.nodeType);
                        return !1
                    }
                }), target: function (t) {
                    var n = e.location && e.location.hash;
                    return n && n.slice(1) === t.id
                }, root: function (e) {
                    return e === f
                }, focus: function (e) {
                    return e === p.activeElement && (!p.hasFocus || p.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                }, enabled: function (e) {
                    return e.disabled === !1
                }, disabled: function (e) {
                    return e.disabled === !0
                }, checked: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && !!e.checked || "option" === t && !!e.selected
                }, selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                }, empty: function (e) {
                    for (e = e.firstChild; e; e = e.nextSibling)if (e.nodeName > "@" || 3 === e.nodeType || 4 === e.nodeType)return !1;
                    return !0
                }, parent: function (e) {
                    return !i.pseudos.empty(e)
                }, header: function (e) {
                    return et.test(e.nodeName)
                }, input: function (e) {
                    return Z.test(e.nodeName)
                }, button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                }, text: function (e) {
                    var t;
                    return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || t.toLowerCase() === e.type)
                }, first: ht(function () {
                    return [0]
                }), last: ht(function (e, t) {
                    return [t - 1]
                }), eq: ht(function (e, t, n) {
                    return [0 > n ? n + t : n]
                }), even: ht(function (e, t) {
                    var n = 0;
                    for (; t > n; n += 2)e.push(n);
                    return e
                }), odd: ht(function (e, t) {
                    var n = 1;
                    for (; t > n; n += 2)e.push(n);
                    return e
                }), lt: ht(function (e, t, n) {
                    var r = 0 > n ? n + t : n;
                    for (; --r >= 0;)e.push(r);
                    return e
                }), gt: ht(function (e, t, n) {
                    var r = 0 > n ? n + t : n;
                    for (; t > ++r;)e.push(r);
                    return e
                })
            }
        }, i.pseudos.nth = i.pseudos.eq;
        for (t in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0})i.pseudos[t] = pt(t);
        for (t in{submit: !0, reset: !0})i.pseudos[t] = ft(t);
        function dt() {
        }

        dt.prototype = i.filters = i.pseudos, i.setFilters = new dt;
        function gt(e, t) {
            var n, r, o, s, a, u, l, c = k[e + " "];
            if (c)return t ? 0 : c.slice(0);
            a = e, u = [], l = i.preFilter;
            while (a) {
                (!n || (r = _.exec(a))) && (r && (a = a.slice(r[0].length) || a), u.push(o = [])), n = !1, (r = X.exec(a)) && (n = r.shift(), o.push({
                    value: n,
                    type: r[0].replace(z, " ")
                }), a = a.slice(n.length));
                for (s in i.filter)!(r = J[s].exec(a)) || l[s] && !(r = l[s](r)) || (n = r.shift(), o.push({
                    value: n,
                    type: s,
                    matches: r
                }), a = a.slice(n.length));
                if (!n)break
            }
            return t ? a.length : a ? ot.error(e) : k(e, u).slice(0)
        }

        function mt(e) {
            var t = 0, n = e.length, r = "";
            for (; n > t; t++)r += e[t].value;
            return r
        }

        function yt(e, t, n) {
            var i = t.dir, o = n && "parentNode" === i, s = T++;
            return t.first ? function (t, n, r) {
                while (t = t[i])if (1 === t.nodeType || o)return e(t, n, r)
            } : function (t, n, a) {
                var u, l, c, p = w + " " + s;
                if (a) {
                    while (t = t[i])if ((1 === t.nodeType || o) && e(t, n, a))return !0
                } else while (t = t[i])if (1 === t.nodeType || o)if (c = t[v] || (t[v] = {}), (l = c[i]) && l[0] === p) {
                    if ((u = l[1]) === !0 || u === r)return u === !0
                } else if (l = c[i] = [p], l[1] = e(t, n, a) || r, l[1] === !0)return !0
            }
        }

        function vt(e) {
            return e.length > 1 ? function (t, n, r) {
                var i = e.length;
                while (i--)if (!e[i](t, n, r))return !1;
                return !0
            } : e[0]
        }

        function xt(e, t, n, r, i) {
            var o, s = [], a = 0, u = e.length, l = null != t;
            for (; u > a; a++)(o = e[a]) && (!n || n(o, r, i)) && (s.push(o), l && t.push(a));
            return s
        }

        function bt(e, t, n, r, i, o) {
            return r && !r[v] && (r = bt(r)), i && !i[v] && (i = bt(i, o)), at(function (o, s, a, u) {
                var l, c, p, f = [], h = [], d = s.length, g = o || Ct(t || "*", a.nodeType ? [a] : a, []), m = !e || !o && t ? g : xt(g, f, e, a, u), y = n ? i || (o ? e : d || r) ? [] : s : m;
                if (n && n(m, y, a, u), r) {
                    l = xt(y, h), r(l, [], a, u), c = l.length;
                    while (c--)(p = l[c]) && (y[h[c]] = !(m[h[c]] = p))
                }
                if (o) {
                    if (i || e) {
                        if (i) {
                            l = [], c = y.length;
                            while (c--)(p = y[c]) && l.push(m[c] = p);
                            i(null, y = [], l, u)
                        }
                        c = y.length;
                        while (c--)(p = y[c]) && (l = i ? P.call(o, p) : f[c]) > -1 && (o[l] = !(s[l] = p))
                    }
                } else y = xt(y === s ? y.splice(d, y.length) : y), i ? i(null, s, y, u) : O.apply(s, y)
            })
        }

        function wt(e) {
            var t, n, r, o = e.length, s = i.relative[e[0].type], a = s || i.relative[" "], l = s ? 1 : 0, c = yt(function (e) {
                return e === t
            }, a, !0), p = yt(function (e) {
                return P.call(t, e) > -1
            }, a, !0), f = [function (e, n, r) {
                return !s && (r || n !== u) || ((t = n).nodeType ? c(e, n, r) : p(e, n, r))
            }];
            for (; o > l; l++)if (n = i.relative[e[l].type])f = [yt(vt(f), n)]; else {
                if (n = i.filter[e[l].type].apply(null, e[l].matches), n[v]) {
                    for (r = ++l; o > r; r++)if (i.relative[e[r].type])break;
                    return bt(l > 1 && vt(f), l > 1 && mt(e.slice(0, l - 1).concat({value: " " === e[l - 2].type ? "*" : ""})).replace(z, "$1"), n, r > l && wt(e.slice(l, r)), o > r && wt(e = e.slice(r)), o > r && mt(e))
                }
                f.push(n)
            }
            return vt(f)
        }

        function Tt(e, t) {
            var n = 0, o = t.length > 0, s = e.length > 0, a = function (a, l, c, f, h) {
                var d, g, m, y = [], v = 0, x = "0", b = a && [], T = null != h, C = u, k = a || s && i.find.TAG("*", h && l.parentNode || l), N = w += null == C ? 1 : Math.random() || .1;
                for (T && (u = l !== p && l, r = n); null != (d = k[x]); x++) {
                    if (s && d) {
                        g = 0;
                        while (m = e[g++])if (m(d, l, c)) {
                            f.push(d);
                            break
                        }
                        T && (w = N, r = ++n)
                    }
                    o && ((d = !m && d) && v--, a && b.push(d))
                }
                if (v += x, o && x !== v) {
                    g = 0;
                    while (m = t[g++])m(b, y, l, c);
                    if (a) {
                        if (v > 0)while (x--)b[x] || y[x] || (y[x] = q.call(f));
                        y = xt(y)
                    }
                    O.apply(f, y), T && !a && y.length > 0 && v + t.length > 1 && ot.uniqueSort(f)
                }
                return T && (w = N, u = C), b
            };
            return o ? at(a) : a
        }

        a = ot.compile = function (e, t) {
            var n, r = [], i = [], o = N[e + " "];
            if (!o) {
                t || (t = gt(e)), n = t.length;
                while (n--)o = wt(t[n]), o[v] ? r.push(o) : i.push(o);
                o = N(e, Tt(i, r))
            }
            return o
        };
        function Ct(e, t, n) {
            var r = 0, i = t.length;
            for (; i > r; r++)ot(e, t[r], n);
            return n
        }

        function kt(e, t, r, o) {
            var s, u, l, c, p, f = gt(e);
            if (!o && 1 === f.length) {
                if (u = f[0] = f[0].slice(0), u.length > 2 && "ID" === (l = u[0]).type && n.getById && 9 === t.nodeType && h && i.relative[u[1].type]) {
                    if (t = (i.find.ID(l.matches[0].replace(nt, rt), t) || [])[0], !t)return r;
                    e = e.slice(u.shift().value.length)
                }
                s = J.needsContext.test(e) ? 0 : u.length;
                while (s--) {
                    if (l = u[s], i.relative[c = l.type])break;
                    if ((p = i.find[c]) && (o = p(l.matches[0].replace(nt, rt), U.test(u[0].type) && t.parentNode || t))) {
                        if (u.splice(s, 1), e = o.length && mt(u), !e)return O.apply(r, o), r;
                        break
                    }
                }
            }
            return a(e, f)(o, t, !h, r, U.test(e)), r
        }

        n.sortStable = v.split("").sort(S).join("") === v, n.detectDuplicates = E, c(), n.sortDetached = ut(function (e) {
            return 1 & e.compareDocumentPosition(p.createElement("div"))
        }), ut(function (e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || lt("type|href|height|width", function (e, t, n) {
            return n ? undefined : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), n.attributes && ut(function (e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || lt("value", function (e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? undefined : e.defaultValue
        }), ut(function (e) {
            return null == e.getAttribute("disabled")
        }) || lt(R, function (e, t, n) {
            var r;
            return n ? undefined : (r = e.getAttributeNode(t)) && r.specified ? r.value : e[t] === !0 ? t.toLowerCase() : null
        }), x.find = ot, x.expr = ot.selectors, x.expr[":"] = x.expr.pseudos, x.unique = ot.uniqueSort, x.text = ot.getText, x.isXMLDoc = ot.isXML, x.contains = ot.contains
    }(e);
    var D = {};

    function A(e) {
        var t = D[e] = {};
        return x.each(e.match(w) || [], function (e, n) {
            t[n] = !0
        }), t
    }

    x.Callbacks = function (e) {
        e = "string" == typeof e ? D[e] || A(e) : x.extend({}, e);
        var t, n, r, i, o, s, a = [], u = !e.once && [], l = function (p) {
            for (t = e.memory && p, n = !0, s = i || 0, i = 0, o = a.length, r = !0; a && o > s; s++)if (a[s].apply(p[0], p[1]) === !1 && e.stopOnFalse) {
                t = !1;
                break
            }
            r = !1, a && (u ? u.length && l(u.shift()) : t ? a = [] : c.disable())
        }, c = {
            add: function () {
                if (a) {
                    var n = a.length;
                    (function s(t) {
                        x.each(t, function (t, n) {
                            var r = x.type(n);
                            "function" === r ? e.unique && c.has(n) || a.push(n) : n && n.length && "string" !== r && s(n)
                        })
                    })(arguments), r ? o = a.length : t && (i = n, l(t))
                }
                return this
            }, remove: function () {
                return a && x.each(arguments, function (e, t) {
                    var n;
                    while ((n = x.inArray(t, a, n)) > -1)a.splice(n, 1), r && (o >= n && o--, s >= n && s--)
                }), this
            }, has: function (e) {
                return e ? x.inArray(e, a) > -1 : !(!a || !a.length)
            }, empty: function () {
                return a = [], o = 0, this
            }, disable: function () {
                return a = u = t = undefined, this
            }, disabled: function () {
                return !a
            }, lock: function () {
                return u = undefined, t || c.disable(), this
            }, locked: function () {
                return !u
            }, fireWith: function (e, t) {
                return !a || n && !u || (t = t || [], t = [e, t.slice ? t.slice() : t], r ? u.push(t) : l(t)), this
            }, fire: function () {
                return c.fireWith(this, arguments), this
            }, fired: function () {
                return !!n
            }
        };
        return c
    }, x.extend({
        Deferred: function (e) {
            var t = [["resolve", "done", x.Callbacks("once memory"), "resolved"], ["reject", "fail", x.Callbacks("once memory"), "rejected"], ["notify", "progress", x.Callbacks("memory")]], n = "pending", r = {
                state: function () {
                    return n
                }, always: function () {
                    return i.done(arguments).fail(arguments), this
                }, then: function () {
                    var e = arguments;
                    return x.Deferred(function (n) {
                        x.each(t, function (t, o) {
                            var s = o[0], a = x.isFunction(e[t]) && e[t];
                            i[o[1]](function () {
                                var e = a && a.apply(this, arguments);
                                e && x.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[s + "With"](this === r ? n.promise() : this, a ? [e] : arguments)
                            })
                        }), e = null
                    }).promise()
                }, promise: function (e) {
                    return null != e ? x.extend(e, r) : r
                }
            }, i = {};
            return r.pipe = r.then, x.each(t, function (e, o) {
                var s = o[2], a = o[3];
                r[o[1]] = s.add, a && s.add(function () {
                    n = a
                }, t[1 ^ e][2].disable, t[2][2].lock), i[o[0]] = function () {
                    return i[o[0] + "With"](this === i ? r : this, arguments), this
                }, i[o[0] + "With"] = s.fireWith
            }), r.promise(i), e && e.call(i, i), i
        }, when: function (e) {
            var t = 0, n = d.call(arguments), r = n.length, i = 1 !== r || e && x.isFunction(e.promise) ? r : 0, o = 1 === i ? e : x.Deferred(), s = function (e, t, n) {
                return function (r) {
                    t[e] = this, n[e] = arguments.length > 1 ? d.call(arguments) : r, n === a ? o.notifyWith(t, n) : --i || o.resolveWith(t, n)
                }
            }, a, u, l;
            if (r > 1)for (a = Array(r), u = Array(r), l = Array(r); r > t; t++)n[t] && x.isFunction(n[t].promise) ? n[t].promise().done(s(t, l, n)).fail(o.reject).progress(s(t, u, a)) : --i;
            return i || o.resolveWith(l, n), o.promise()
        }
    }), x.support = function (t) {
        var n = o.createElement("input"), r = o.createDocumentFragment(), i = o.createElement("div"), s = o.createElement("select"), a = s.appendChild(o.createElement("option"));
        return n.type ? (n.type = "checkbox", t.checkOn = "" !== n.value, t.optSelected = a.selected, t.reliableMarginRight = !0, t.boxSizingReliable = !0, t.pixelPosition = !1, n.checked = !0, t.noCloneChecked = n.cloneNode(!0).checked, s.disabled = !0, t.optDisabled = !a.disabled, n = o.createElement("input"), n.value = "t", n.type = "radio", t.radioValue = "t" === n.value, n.setAttribute("checked", "t"), n.setAttribute("name", "t"), r.appendChild(n), t.checkClone = r.cloneNode(!0).cloneNode(!0).lastChild.checked, t.focusinBubbles = "onfocusin"in e, i.style.backgroundClip = "content-box", i.cloneNode(!0).style.backgroundClip = "", t.clearCloneStyle = "content-box" === i.style.backgroundClip, x(function () {
            var n, r, s = "padding:0;margin:0;border:0;display:block;-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box", a = o.getElementsByTagName("body")[0];
            a && (n = o.createElement("div"), n.style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", a.appendChild(n).appendChild(i), i.innerHTML = "", i.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%", x.swap(a, null != a.style.zoom ? {zoom: 1} : {}, function () {
                t.boxSizing = 4 === i.offsetWidth
            }), e.getComputedStyle && (t.pixelPosition = "1%" !== (e.getComputedStyle(i, null) || {}).top, t.boxSizingReliable = "4px" === (e.getComputedStyle(i, null) || {width: "4px"}).width, r = i.appendChild(o.createElement("div")), r.style.cssText = i.style.cssText = s, r.style.marginRight = r.style.width = "0", i.style.width = "1px", t.reliableMarginRight = !parseFloat((e.getComputedStyle(r, null) || {}).marginRight)), a.removeChild(n))
        }), t) : t
    }({});
    var L, q, H = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/, O = /([A-Z])/g;

    function F() {
        Object.defineProperty(this.cache = {}, 0, {
            get: function () {
                return {}
            }
        }), this.expando = x.expando + Math.random()
    }

    F.uid = 1, F.accepts = function (e) {
        return e.nodeType ? 1 === e.nodeType || 9 === e.nodeType : !0
    }, F.prototype = {
        key: function (e) {
            if (!F.accepts(e))return 0;
            var t = {}, n = e[this.expando];
            if (!n) {
                n = F.uid++;
                try {
                    t[this.expando] = {value: n}, Object.defineProperties(e, t)
                } catch (r) {
                    t[this.expando] = n, x.extend(e, t)
                }
            }
            return this.cache[n] || (this.cache[n] = {}), n
        }, set: function (e, t, n) {
            var r, i = this.key(e), o = this.cache[i];
            if ("string" == typeof t)o[t] = n; else if (x.isEmptyObject(o))x.extend(this.cache[i], t); else for (r in t)o[r] = t[r];
            return o
        }, get: function (e, t) {
            var n = this.cache[this.key(e)];
            return t === undefined ? n : n[t]
        }, access: function (e, t, n) {
            var r;
            return t === undefined || t && "string" == typeof t && n === undefined ? (r = this.get(e, t), r !== undefined ? r : this.get(e, x.camelCase(t))) : (this.set(e, t, n), n !== undefined ? n : t)
        }, remove: function (e, t) {
            var n, r, i, o = this.key(e), s = this.cache[o];
            if (t === undefined)this.cache[o] = {}; else {
                x.isArray(t) ? r = t.concat(t.map(x.camelCase)) : (i = x.camelCase(t), t in s ? r = [t, i] : (r = i, r = r in s ? [r] : r.match(w) || [])), n = r.length;
                while (n--)delete s[r[n]]
            }
        }, hasData: function (e) {
            return !x.isEmptyObject(this.cache[e[this.expando]] || {})
        }, discard: function (e) {
            e[this.expando] && delete this.cache[e[this.expando]]
        }
    }, L = new F, q = new F, x.extend({
        acceptData: F.accepts, hasData: function (e) {
            return L.hasData(e) || q.hasData(e)
        }, data: function (e, t, n) {
            return L.access(e, t, n)
        }, removeData: function (e, t) {
            L.remove(e, t)
        }, _data: function (e, t, n) {
            return q.access(e, t, n)
        }, _removeData: function (e, t) {
            q.remove(e, t)
        }
    }), x.fn.extend({
        data: function (e, t) {
            var n, r, i = this[0], o = 0, s = null;
            if (e === undefined) {
                if (this.length && (s = L.get(i), 1 === i.nodeType && !q.get(i, "hasDataAttrs"))) {
                    for (n = i.attributes; n.length > o; o++)r = n[o].name, 0 === r.indexOf("data-") && (r = x.camelCase(r.slice(5)), P(i, r, s[r]));
                    q.set(i, "hasDataAttrs", !0)
                }
                return s
            }
            return "object" == typeof e ? this.each(function () {
                L.set(this, e)
            }) : x.access(this, function (t) {
                var n, r = x.camelCase(e);
                if (i && t === undefined) {
                    if (n = L.get(i, e), n !== undefined)return n;
                    if (n = L.get(i, r), n !== undefined)return n;
                    if (n = P(i, r, undefined), n !== undefined)return n
                } else this.each(function () {
                    var n = L.get(this, r);
                    L.set(this, r, t), -1 !== e.indexOf("-") && n !== undefined && L.set(this, e, t)
                })
            }, null, t, arguments.length > 1, null, !0)
        }, removeData: function (e) {
            return this.each(function () {
                L.remove(this, e)
            })
        }
    });
    function P(e, t, n) {
        var r;
        if (n === undefined && 1 === e.nodeType)if (r = "data-" + t.replace(O, "-$1").toLowerCase(), n = e.getAttribute(r), "string" == typeof n) {
            try {
                n = "true" === n ? !0 : "false" === n ? !1 : "null" === n ? null : +n + "" === n ? +n : H.test(n) ? JSON.parse(n) : n
            } catch (i) {
            }
            L.set(e, t, n)
        } else n = undefined;
        return n
    }

    x.extend({
        queue: function (e, t, n) {
            var r;
            return e ? (t = (t || "fx") + "queue", r = q.get(e, t), n && (!r || x.isArray(n) ? r = q.access(e, t, x.makeArray(n)) : r.push(n)), r || []) : undefined
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = x.queue(e, t), r = n.length, i = n.shift(), o = x._queueHooks(e, t), s = function () {
                x.dequeue(e, t)
            };
            "inprogress" === i && (i = n.shift(), r--), i && ("fx" === t && n.unshift("inprogress"), delete o.stop, i.call(e, s, o)), !r && o && o.empty.fire()
        }, _queueHooks: function (e, t) {
            var n = t + "queueHooks";
            return q.get(e, n) || q.access(e, n, {
                    empty: x.Callbacks("once memory").add(function () {
                        q.remove(e, [t + "queue", n])
                    })
                })
        }
    }), x.fn.extend({
        queue: function (e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--), n > arguments.length ? x.queue(this[0], e) : t === undefined ? this : this.each(function () {
                var n = x.queue(this, e, t);
                x._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && x.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                x.dequeue(this, e)
            })
        }, delay: function (e, t) {
            return e = x.fx ? x.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, n) {
                var r = setTimeout(t, e);
                n.stop = function () {
                    clearTimeout(r)
                }
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, t) {
            var n, r = 1, i = x.Deferred(), o = this, s = this.length, a = function () {
                --r || i.resolveWith(o, [o])
            };
            "string" != typeof e && (t = e, e = undefined), e = e || "fx";
            while (s--)n = q.get(o[s], e + "queueHooks"), n && n.empty && (r++, n.empty.add(a));
            return a(), i.promise(t)
        }
    });
    var R, M, W = /[\t\r\n\f]/g, $ = /\r/g, B = /^(?:input|select|textarea|button)$/i;
    x.fn.extend({
        attr: function (e, t) {
            return x.access(this, x.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                x.removeAttr(this, e)
            })
        }, prop: function (e, t) {
            return x.access(this, x.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return this.each(function () {
                delete this[x.propFix[e] || e]
            })
        }, addClass: function (e) {
            var t, n, r, i, o, s = 0, a = this.length, u = "string" == typeof e && e;
            if (x.isFunction(e))return this.each(function (t) {
                x(this).addClass(e.call(this, t, this.className))
            });
            if (u)for (t = (e || "").match(w) || []; a > s; s++)if (n = this[s], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(W, " ") : " ")) {
                o = 0;
                while (i = t[o++])0 > r.indexOf(" " + i + " ") && (r += i + " ");
                n.className = x.trim(r)
            }
            return this
        }, removeClass: function (e) {
            var t, n, r, i, o, s = 0, a = this.length, u = 0 === arguments.length || "string" == typeof e && e;
            if (x.isFunction(e))return this.each(function (t) {
                x(this).removeClass(e.call(this, t, this.className))
            });
            if (u)for (t = (e || "").match(w) || []; a > s; s++)if (n = this[s], r = 1 === n.nodeType && (n.className ? (" " + n.className + " ").replace(W, " ") : "")) {
                o = 0;
                while (i = t[o++])while (r.indexOf(" " + i + " ") >= 0)r = r.replace(" " + i + " ", " ");
                n.className = e ? x.trim(r) : ""
            }
            return this
        }, toggleClass: function (e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : x.isFunction(e) ? this.each(function (n) {
                x(this).toggleClass(e.call(this, n, this.className, t), t)
            }) : this.each(function () {
                if ("string" === n) {
                    var t, i = 0, o = x(this), s = e.match(w) || [];
                    while (t = s[i++])o.hasClass(t) ? o.removeClass(t) : o.addClass(t)
                } else(n === r || "boolean" === n) && (this.className && q.set(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : q.get(this, "__className__") || "")
            })
        }, hasClass: function (e) {
            var t = " " + e + " ", n = 0, r = this.length;
            for (; r > n; n++)if (1 === this[n].nodeType && (" " + this[n].className + " ").replace(W, " ").indexOf(t) >= 0)return !0;
            return !1
        }, val: function (e) {
            var t, n, r, i = this[0];
            {
                if (arguments.length)return r = x.isFunction(e), this.each(function (n) {
                    var i;
                    1 === this.nodeType && (i = r ? e.call(this, n, x(this).val()) : e, null == i ? i = "" : "number" == typeof i ? i += "" : x.isArray(i) && (i = x.map(i, function (e) {
                        return null == e ? "" : e + ""
                    })), t = x.valHooks[this.type] || x.valHooks[this.nodeName.toLowerCase()], t && "set"in t && t.set(this, i, "value") !== undefined || (this.value = i))
                });
                if (i)return t = x.valHooks[i.type] || x.valHooks[i.nodeName.toLowerCase()], t && "get"in t && (n = t.get(i, "value")) !== undefined ? n : (n = i.value, "string" == typeof n ? n.replace($, "") : null == n ? "" : n)
            }
        }
    }), x.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = e.attributes.value;
                    return !t || t.specified ? e.value : e.text
                }
            }, select: {
                get: function (e) {
                    var t, n, r = e.options, i = e.selectedIndex, o = "select-one" === e.type || 0 > i, s = o ? null : [], a = o ? i + 1 : r.length, u = 0 > i ? a : o ? i : 0;
                    for (; a > u; u++)if (n = r[u], !(!n.selected && u !== i || (x.support.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && x.nodeName(n.parentNode, "optgroup"))) {
                        if (t = x(n).val(), o)return t;
                        s.push(t)
                    }
                    return s
                }, set: function (e, t) {
                    var n, r, i = e.options, o = x.makeArray(t), s = i.length;
                    while (s--)r = i[s], (r.selected = x.inArray(x(r).val(), o) >= 0) && (n = !0);
                    return n || (e.selectedIndex = -1), o
                }
            }
        }, attr: function (e, t, n) {
            var i, o, s = e.nodeType;
            if (e && 3 !== s && 8 !== s && 2 !== s)return typeof e.getAttribute === r ? x.prop(e, t, n) : (1 === s && x.isXMLDoc(e) || (t = t.toLowerCase(), i = x.attrHooks[t] || (x.expr.match.bool.test(t) ? M : R)), n === undefined ? i && "get"in i && null !== (o = i.get(e, t)) ? o : (o = x.find.attr(e, t), null == o ? undefined : o) : null !== n ? i && "set"in i && (o = i.set(e, n, t)) !== undefined ? o : (e.setAttribute(t, n + ""), n) : (x.removeAttr(e, t), undefined))
        }, removeAttr: function (e, t) {
            var n, r, i = 0, o = t && t.match(w);
            if (o && 1 === e.nodeType)while (n = o[i++])r = x.propFix[n] || n, x.expr.match.bool.test(n) && (e[r] = !1), e.removeAttribute(n)
        }, attrHooks: {
            type: {
                set: function (e, t) {
                    if (!x.support.radioValue && "radio" === t && x.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }, propFix: {"for": "htmlFor", "class": "className"}, prop: function (e, t, n) {
            var r, i, o, s = e.nodeType;
            if (e && 3 !== s && 8 !== s && 2 !== s)return o = 1 !== s || !x.isXMLDoc(e), o && (t = x.propFix[t] || t, i = x.propHooks[t]), n !== undefined ? i && "set"in i && (r = i.set(e, n, t)) !== undefined ? r : e[t] = n : i && "get"in i && null !== (r = i.get(e, t)) ? r : e[t]
        }, propHooks: {
            tabIndex: {
                get: function (e) {
                    return e.hasAttribute("tabindex") || B.test(e.nodeName) || e.href ? e.tabIndex : -1
                }
            }
        }
    }), M = {
        set: function (e, t, n) {
            return t === !1 ? x.removeAttr(e, n) : e.setAttribute(n, n), n
        }
    }, x.each(x.expr.match.bool.source.match(/\w+/g), function (e, t) {
        var n = x.expr.attrHandle[t] || x.find.attr;
        x.expr.attrHandle[t] = function (e, t, r) {
            var i = x.expr.attrHandle[t], o = r ? undefined : (x.expr.attrHandle[t] = undefined) != n(e, t, r) ? t.toLowerCase() : null;
            return x.expr.attrHandle[t] = i, o
        }
    }), x.support.optSelected || (x.propHooks.selected = {
        get: function (e) {
            var t = e.parentNode;
            return t && t.parentNode && t.parentNode.selectedIndex, null
        }
    }), x.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
        x.propFix[this.toLowerCase()] = this
    }), x.each(["radio", "checkbox"], function () {
        x.valHooks[this] = {
            set: function (e, t) {
                return x.isArray(t) ? e.checked = x.inArray(x(e).val(), t) >= 0 : undefined
            }
        }, x.support.checkOn || (x.valHooks[this].get = function (e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var I = /^key/, z = /^(?:mouse|contextmenu)|click/, _ = /^(?:focusinfocus|focusoutblur)$/, X = /^([^.]*)(?:\.(.+)|)$/;

    function U() {
        return !0
    }

    function Y() {
        return !1
    }

    function V() {
        try {
            return o.activeElement
        } catch (e) {
        }
    }

    x.event = {
        global: {},
        add: function (e, t, n, i, o) {
            var s, a, u, l, c, p, f, h, d, g, m, y = q.get(e);
            if (y) {
                n.handler && (s = n, n = s.handler, o = s.selector), n.guid || (n.guid = x.guid++), (l = y.events) || (l = y.events = {}), (a = y.handle) || (a = y.handle = function (e) {
                    return typeof x === r || e && x.event.triggered === e.type ? undefined : x.event.dispatch.apply(a.elem, arguments)
                }, a.elem = e), t = (t || "").match(w) || [""], c = t.length;
                while (c--)u = X.exec(t[c]) || [], d = m = u[1], g = (u[2] || "").split(".").sort(), d && (f = x.event.special[d] || {}, d = (o ? f.delegateType : f.bindType) || d, f = x.event.special[d] || {}, p = x.extend({
                    type: d,
                    origType: m,
                    data: i,
                    handler: n,
                    guid: n.guid,
                    selector: o,
                    needsContext: o && x.expr.match.needsContext.test(o),
                    namespace: g.join(".")
                }, s), (h = l[d]) || (h = l[d] = [], h.delegateCount = 0, f.setup && f.setup.call(e, i, g, a) !== !1 || e.addEventListener && e.addEventListener(d, a, !1)), f.add && (f.add.call(e, p), p.handler.guid || (p.handler.guid = n.guid)), o ? h.splice(h.delegateCount++, 0, p) : h.push(p), x.event.global[d] = !0);
                e = null
            }
        },
        remove: function (e, t, n, r, i) {
            var o, s, a, u, l, c, p, f, h, d, g, m = q.hasData(e) && q.get(e);
            if (m && (u = m.events)) {
                t = (t || "").match(w) || [""], l = t.length;
                while (l--)if (a = X.exec(t[l]) || [], h = g = a[1], d = (a[2] || "").split(".").sort(), h) {
                    p = x.event.special[h] || {}, h = (r ? p.delegateType : p.bindType) || h, f = u[h] || [], a = a[2] && RegExp("(^|\\.)" + d.join("\\.(?:.*\\.|)") + "(\\.|$)"), s = o = f.length;
                    while (o--)c = f[o], !i && g !== c.origType || n && n.guid !== c.guid || a && !a.test(c.namespace) || r && r !== c.selector && ("**" !== r || !c.selector) || (f.splice(o, 1), c.selector && f.delegateCount--, p.remove && p.remove.call(e, c));
                    s && !f.length && (p.teardown && p.teardown.call(e, d, m.handle) !== !1 || x.removeEvent(e, h, m.handle), delete u[h])
                } else for (h in u)x.event.remove(e, h + t[l], n, r, !0);
                x.isEmptyObject(u) && (delete m.handle, q.remove(e, "events"))
            }
        },
        trigger: function (t, n, r, i) {
            var s, a, u, l, c, p, f, h = [r || o], d = y.call(t, "type") ? t.type : t, g = y.call(t, "namespace") ? t.namespace.split(".") : [];
            if (a = u = r = r || o, 3 !== r.nodeType && 8 !== r.nodeType && !_.test(d + x.event.triggered) && (d.indexOf(".") >= 0 && (g = d.split("."), d = g.shift(), g.sort()), c = 0 > d.indexOf(":") && "on" + d, t = t[x.expando] ? t : new x.Event(d, "object" == typeof t && t), t.isTrigger = i ? 2 : 3, t.namespace = g.join("."), t.namespace_re = t.namespace ? RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = undefined, t.target || (t.target = r), n = null == n ? [t] : x.makeArray(n, [t]), f = x.event.special[d] || {}, i || !f.trigger || f.trigger.apply(r, n) !== !1)) {
                if (!i && !f.noBubble && !x.isWindow(r)) {
                    for (l = f.delegateType || d, _.test(l + d) || (a = a.parentNode); a; a = a.parentNode)h.push(a), u = a;
                    u === (r.ownerDocument || o) && h.push(u.defaultView || u.parentWindow || e)
                }
                s = 0;
                while ((a = h[s++]) && !t.isPropagationStopped())t.type = s > 1 ? l : f.bindType || d, p = (q.get(a, "events") || {})[t.type] && q.get(a, "handle"), p && p.apply(a, n), p = c && a[c], p && x.acceptData(a) && p.apply && p.apply(a, n) === !1 && t.preventDefault();
                return t.type = d, i || t.isDefaultPrevented() || f._default && f._default.apply(h.pop(), n) !== !1 || !x.acceptData(r) || c && x.isFunction(r[d]) && !x.isWindow(r) && (u = r[c], u && (r[c] = null), x.event.triggered = d, r[d](), x.event.triggered = undefined, u && (r[c] = u)), t.result
            }
        },
        dispatch: function (e) {
            e = x.event.fix(e);
            var t, n, r, i, o, s = [], a = d.call(arguments), u = (q.get(this, "events") || {})[e.type] || [], l = x.event.special[e.type] || {};
            if (a[0] = e, e.delegateTarget = this, !l.preDispatch || l.preDispatch.call(this, e) !== !1) {
                s = x.event.handlers.call(this, e, u), t = 0;
                while ((i = s[t++]) && !e.isPropagationStopped()) {
                    e.currentTarget = i.elem, n = 0;
                    while ((o = i.handlers[n++]) && !e.isImmediatePropagationStopped())(!e.namespace_re || e.namespace_re.test(o.namespace)) && (e.handleObj = o, e.data = o.data, r = ((x.event.special[o.origType] || {}).handle || o.handler).apply(i.elem, a), r !== undefined && (e.result = r) === !1 && (e.preventDefault(), e.stopPropagation()))
                }
                return l.postDispatch && l.postDispatch.call(this, e), e.result
            }
        },
        handlers: function (e, t) {
            var n, r, i, o, s = [], a = t.delegateCount, u = e.target;
            if (a && u.nodeType && (!e.button || "click" !== e.type))for (; u !== this; u = u.parentNode || this)if (u.disabled !== !0 || "click" !== e.type) {
                for (r = [], n = 0; a > n; n++)o = t[n], i = o.selector + " ", r[i] === undefined && (r[i] = o.needsContext ? x(i, this).index(u) >= 0 : x.find(i, this, null, [u]).length), r[i] && r.push(o);
                r.length && s.push({elem: u, handlers: r})
            }
            return t.length > a && s.push({elem: this, handlers: t.slice(a)}), s
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function (e, t) {
                var n, r, i, s = t.button;
                return null == e.pageX && null != t.clientX && (n = e.target.ownerDocument || o, r = n.documentElement, i = n.body, e.pageX = t.clientX + (r && r.scrollLeft || i && i.scrollLeft || 0) - (r && r.clientLeft || i && i.clientLeft || 0), e.pageY = t.clientY + (r && r.scrollTop || i && i.scrollTop || 0) - (r && r.clientTop || i && i.clientTop || 0)), e.which || s === undefined || (e.which = 1 & s ? 1 : 2 & s ? 3 : 4 & s ? 2 : 0), e
            }
        },
        fix: function (e) {
            if (e[x.expando])return e;
            var t, n, r, i = e.type, s = e, a = this.fixHooks[i];
            a || (this.fixHooks[i] = a = z.test(i) ? this.mouseHooks : I.test(i) ? this.keyHooks : {}), r = a.props ? this.props.concat(a.props) : this.props, e = new x.Event(s), t = r.length;
            while (t--)n = r[t], e[n] = s[n];
            return e.target || (e.target = o), 3 === e.target.nodeType && (e.target = e.target.parentNode), a.filter ? a.filter(e, s) : e
        },
        special: {
            load: {noBubble: !0}, focus: {
                trigger: function () {
                    return this !== V() && this.focus ? (this.focus(), !1) : undefined
                }, delegateType: "focusin"
            }, blur: {
                trigger: function () {
                    return this === V() && this.blur ? (this.blur(), !1) : undefined
                }, delegateType: "focusout"
            }, click: {
                trigger: function () {
                    return "checkbox" === this.type && this.click && x.nodeName(this, "input") ? (this.click(), !1) : undefined
                }, _default: function (e) {
                    return x.nodeName(e.target, "a")
                }
            }, beforeunload: {
                postDispatch: function (e) {
                    e.result !== undefined && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function (e, t, n, r) {
            var i = x.extend(new x.Event, n, {type: e, isSimulated: !0, originalEvent: {}});
            r ? x.event.trigger(i, null, t) : x.event.dispatch.call(t, i), i.isDefaultPrevented() && n.preventDefault()
        }
    }, x.removeEvent = function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    }, x.Event = function (e, t) {
        return this instanceof x.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || e.getPreventDefault && e.getPreventDefault() ? U : Y) : this.type = e, t && x.extend(this, t), this.timeStamp = e && e.timeStamp || x.now(), this[x.expando] = !0, undefined) : new x.Event(e, t)
    }, x.Event.prototype = {
        isDefaultPrevented: Y,
        isPropagationStopped: Y,
        isImmediatePropagationStopped: Y,
        preventDefault: function () {
            var e = this.originalEvent;
            this.isDefaultPrevented = U, e && e.preventDefault && e.preventDefault()
        },
        stopPropagation: function () {
            var e = this.originalEvent;
            this.isPropagationStopped = U, e && e.stopPropagation && e.stopPropagation()
        },
        stopImmediatePropagation: function () {
            this.isImmediatePropagationStopped = U, this.stopPropagation()
        }
    }, x.each({mouseenter: "mouseover", mouseleave: "mouseout"}, function (e, t) {
        x.event.special[e] = {
            delegateType: t, bindType: t, handle: function (e) {
                var n, r = this, i = e.relatedTarget, o = e.handleObj;
                return (!i || i !== r && !x.contains(r, i)) && (e.type = o.origType, n = o.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), x.support.focusinBubbles || x.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = 0, r = function (e) {
            x.event.simulate(t, e.target, x.event.fix(e), !0)
        };
        x.event.special[t] = {
            setup: function () {
                0 === n++ && o.addEventListener(e, r, !0)
            }, teardown: function () {
                0 === --n && o.removeEventListener(e, r, !0)
            }
        }
    }), x.fn.extend({
        on: function (e, t, n, r, i) {
            var o, s;
            if ("object" == typeof e) {
                "string" != typeof t && (n = n || t, t = undefined);
                for (s in e)this.on(s, t, n, e[s], i);
                return this
            }
            if (null == n && null == r ? (r = t, n = t = undefined) : null == r && ("string" == typeof t ? (r = n, n = undefined) : (r = n, n = t, t = undefined)), r === !1)r = Y; else if (!r)return this;
            return 1 === i && (o = r, r = function (e) {
                return x().off(e), o.apply(this, arguments)
            }, r.guid = o.guid || (o.guid = x.guid++)), this.each(function () {
                x.event.add(this, e, r, n, t)
            })
        }, one: function (e, t, n, r) {
            return this.on(e, t, n, r, 1)
        }, off: function (e, t, n) {
            var r, i;
            if (e && e.preventDefault && e.handleObj)return r = e.handleObj, x(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this;
            if ("object" == typeof e) {
                for (i in e)this.off(i, t, e[i]);
                return this
            }
            return (t === !1 || "function" == typeof t) && (n = t, t = undefined), n === !1 && (n = Y), this.each(function () {
                x.event.remove(this, e, n, t)
            })
        }, trigger: function (e, t) {
            return this.each(function () {
                x.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            var n = this[0];
            return n ? x.event.trigger(e, t, n, !0) : undefined
        }
    });
    var G = /^.[^:#\[\.,]*$/, J = /^(?:parents|prev(?:Until|All))/, Q = x.expr.match.needsContext, K = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    x.fn.extend({
        find: function (e) {
            var t, n = [], r = this, i = r.length;
            if ("string" != typeof e)return this.pushStack(x(e).filter(function () {
                for (t = 0; i > t; t++)if (x.contains(r[t], this))return !0
            }));
            for (t = 0; i > t; t++)x.find(e, r[t], n);
            return n = this.pushStack(i > 1 ? x.unique(n) : n), n.selector = this.selector ? this.selector + " " + e : e, n
        }, has: function (e) {
            var t = x(e, this), n = t.length;
            return this.filter(function () {
                var e = 0;
                for (; n > e; e++)if (x.contains(this, t[e]))return !0
            })
        }, not: function (e) {
            return this.pushStack(et(this, e || [], !0))
        }, filter: function (e) {
            return this.pushStack(et(this, e || [], !1))
        }, is: function (e) {
            return !!et(this, "string" == typeof e && Q.test(e) ? x(e) : e || [], !1).length
        }, closest: function (e, t) {
            var n, r = 0, i = this.length, o = [], s = Q.test(e) || "string" != typeof e ? x(e, t || this.context) : 0;
            for (; i > r; r++)for (n = this[r]; n && n !== t; n = n.parentNode)if (11 > n.nodeType && (s ? s.index(n) > -1 : 1 === n.nodeType && x.find.matchesSelector(n, e))) {
                n = o.push(n);
                break
            }
            return this.pushStack(o.length > 1 ? x.unique(o) : o)
        }, index: function (e) {
            return e ? "string" == typeof e ? g.call(x(e), this[0]) : g.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        }, add: function (e, t) {
            var n = "string" == typeof e ? x(e, t) : x.makeArray(e && e.nodeType ? [e] : e), r = x.merge(this.get(), n);
            return this.pushStack(x.unique(r))
        }, addBack: function (e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    });
    function Z(e, t) {
        while ((e = e[t]) && 1 !== e.nodeType);
        return e
    }

    x.each({
        parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        }, parents: function (e) {
            return x.dir(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return x.dir(e, "parentNode", n)
        }, next: function (e) {
            return Z(e, "nextSibling")
        }, prev: function (e) {
            return Z(e, "previousSibling")
        }, nextAll: function (e) {
            return x.dir(e, "nextSibling")
        }, prevAll: function (e) {
            return x.dir(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return x.dir(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return x.dir(e, "previousSibling", n)
        }, siblings: function (e) {
            return x.sibling((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return x.sibling(e.firstChild)
        }, contents: function (e) {
            return e.contentDocument || x.merge([], e.childNodes)
        }
    }, function (e, t) {
        x.fn[e] = function (n, r) {
            var i = x.map(this, t, n);
            return "Until" !== e.slice(-5) && (r = n), r && "string" == typeof r && (i = x.filter(r, i)), this.length > 1 && (K[e] || x.unique(i), J.test(e) && i.reverse()), this.pushStack(i)
        }
    }), x.extend({
        filter: function (e, t, n) {
            var r = t[0];
            return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === r.nodeType ? x.find.matchesSelector(r, e) ? [r] : [] : x.find.matches(e, x.grep(t, function (e) {
                return 1 === e.nodeType
            }))
        }, dir: function (e, t, n) {
            var r = [], i = n !== undefined;
            while ((e = e[t]) && 9 !== e.nodeType)if (1 === e.nodeType) {
                if (i && x(e).is(n))break;
                r.push(e)
            }
            return r
        }, sibling: function (e, t) {
            var n = [];
            for (; e; e = e.nextSibling)1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    });
    function et(e, t, n) {
        if (x.isFunction(t))return x.grep(e, function (e, r) {
            return !!t.call(e, r, e) !== n
        });
        if (t.nodeType)return x.grep(e, function (e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (G.test(t))return x.filter(t, e, n);
            t = x.filter(t, e)
        }
        return x.grep(e, function (e) {
            return g.call(t, e) >= 0 !== n
        })
    }

    var tt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, nt = /<([\w:]+)/, rt = /<|&#?\w+;/, it = /<(?:script|style|link)/i, ot = /^(?:checkbox|radio)$/i, st = /checked\s*(?:[^=]|=\s*.checked.)/i, at = /^$|\/(?:java|ecma)script/i, ut = /^true\/(.*)/, lt = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g, ct = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        thead: [1, "<table>", "</table>"],
        col: [2, "<table><colgroup>", "</colgroup></table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        _default: [0, "", ""]
    };
    ct.optgroup = ct.option, ct.tbody = ct.tfoot = ct.colgroup = ct.caption = ct.thead, ct.th = ct.td, x.fn.extend({
        text: function (e) {
            return x.access(this, function (e) {
                return e === undefined ? x.text(this) : this.empty().append((this[0] && this[0].ownerDocument || o).createTextNode(e))
            }, null, e, arguments.length)
        }, append: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = pt(this, e);
                    t.appendChild(e)
                }
            })
        }, prepend: function () {
            return this.domManip(arguments, function (e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = pt(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        }, before: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        }, after: function () {
            return this.domManip(arguments, function (e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        }, remove: function (e, t) {
            var n, r = e ? x.filter(e, this) : this, i = 0;
            for (; null != (n = r[i]); i++)t || 1 !== n.nodeType || x.cleanData(mt(n)), n.parentNode && (t && x.contains(n.ownerDocument, n) && dt(mt(n, "script")), n.parentNode.removeChild(n));
            return this
        }, empty: function () {
            var e, t = 0;
            for (; null != (e = this[t]); t++)1 === e.nodeType && (x.cleanData(mt(e, !1)), e.textContent = "");
            return this
        }, clone: function (e, t) {
            return e = null == e ? !1 : e, t = null == t ? e : t, this.map(function () {
                return x.clone(this, e, t)
            })
        }, html: function (e) {
            return x.access(this, function (e) {
                var t = this[0] || {}, n = 0, r = this.length;
                if (e === undefined && 1 === t.nodeType)return t.innerHTML;
                if ("string" == typeof e && !it.test(e) && !ct[(nt.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = e.replace(tt, "<$1></$2>");
                    try {
                        for (; r > n; n++)t = this[n] || {}, 1 === t.nodeType && (x.cleanData(mt(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (i) {
                    }
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function () {
            var e = x.map(this, function (e) {
                return [e.nextSibling, e.parentNode]
            }), t = 0;
            return this.domManip(arguments, function (n) {
                var r = e[t++], i = e[t++];
                i && (r && r.parentNode !== i && (r = this.nextSibling), x(this).remove(), i.insertBefore(n, r))
            }, !0), t ? this : this.remove()
        }, detach: function (e) {
            return this.remove(e, !0)
        }, domManip: function (e, t, n) {
            e = f.apply([], e);
            var r, i, o, s, a, u, l = 0, c = this.length, p = this, h = c - 1, d = e[0], g = x.isFunction(d);
            if (g || !(1 >= c || "string" != typeof d || x.support.checkClone) && st.test(d))return this.each(function (r) {
                var i = p.eq(r);
                g && (e[0] = d.call(this, r, i.html())), i.domManip(e, t, n)
            });
            if (c && (r = x.buildFragment(e, this[0].ownerDocument, !1, !n && this), i = r.firstChild, 1 === r.childNodes.length && (r = i), i)) {
                for (o = x.map(mt(r, "script"), ft), s = o.length; c > l; l++)a = r, l !== h && (a = x.clone(a, !0, !0), s && x.merge(o, mt(a, "script"))), t.call(this[l], a, l);
                if (s)for (u = o[o.length - 1].ownerDocument, x.map(o, ht), l = 0; s > l; l++)a = o[l], at.test(a.type || "") && !q.access(a, "globalEval") && x.contains(u, a) && (a.src ? x._evalUrl(a.src) : x.globalEval(a.textContent.replace(lt, "")))
            }
            return this
        }
    }), x.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (e, t) {
        x.fn[e] = function (e) {
            var n, r = [], i = x(e), o = i.length - 1, s = 0;
            for (; o >= s; s++)n = s === o ? this : this.clone(!0), x(i[s])[t](n), h.apply(r, n.get());
            return this.pushStack(r)
        }
    }), x.extend({
        clone: function (e, t, n) {
            var r, i, o, s, a = e.cloneNode(!0), u = x.contains(e.ownerDocument, e);
            if (!(x.support.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || x.isXMLDoc(e)))for (s = mt(a), o = mt(e), r = 0, i = o.length; i > r; r++)yt(o[r], s[r]);
            if (t)if (n)for (o = o || mt(e), s = s || mt(a), r = 0, i = o.length; i > r; r++)gt(o[r], s[r]); else gt(e, a);
            return s = mt(a, "script"), s.length > 0 && dt(s, !u && mt(e, "script")), a
        }, buildFragment: function (e, t, n, r) {
            var i, o, s, a, u, l, c = 0, p = e.length, f = t.createDocumentFragment(), h = [];
            for (; p > c; c++)if (i = e[c], i || 0 === i)if ("object" === x.type(i))x.merge(h, i.nodeType ? [i] : i); else if (rt.test(i)) {
                o = o || f.appendChild(t.createElement("div")), s = (nt.exec(i) || ["", ""])[1].toLowerCase(), a = ct[s] || ct._default, o.innerHTML = a[1] + i.replace(tt, "<$1></$2>") + a[2], l = a[0];
                while (l--)o = o.lastChild;
                x.merge(h, o.childNodes), o = f.firstChild, o.textContent = ""
            } else h.push(t.createTextNode(i));
            f.textContent = "", c = 0;
            while (i = h[c++])if ((!r || -1 === x.inArray(i, r)) && (u = x.contains(i.ownerDocument, i), o = mt(f.appendChild(i), "script"), u && dt(o), n)) {
                l = 0;
                while (i = o[l++])at.test(i.type || "") && n.push(i)
            }
            return f
        }, cleanData: function (e) {
            var t, n, r, i, o, s, a = x.event.special, u = 0;
            for (; (n = e[u]) !== undefined; u++) {
                if (F.accepts(n) && (o = n[q.expando], o && (t = q.cache[o]))) {
                    if (r = Object.keys(t.events || {}), r.length)for (s = 0; (i = r[s]) !== undefined; s++)a[i] ? x.event.remove(n, i) : x.removeEvent(n, i, t.handle);
                    q.cache[o] && delete q.cache[o]
                }
                delete L.cache[n[L.expando]]
            }
        }, _evalUrl: function (e) {
            return x.ajax({url: e, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
        }
    });
    function pt(e, t) {
        return x.nodeName(e, "table") && x.nodeName(1 === t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function ft(e) {
        return e.type = (null !== e.getAttribute("type")) + "/" + e.type, e
    }

    function ht(e) {
        var t = ut.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function dt(e, t) {
        var n = e.length, r = 0;
        for (; n > r; r++)q.set(e[r], "globalEval", !t || q.get(t[r], "globalEval"))
    }

    function gt(e, t) {
        var n, r, i, o, s, a, u, l;
        if (1 === t.nodeType) {
            if (q.hasData(e) && (o = q.access(e), s = q.set(t, o), l = o.events)) {
                delete s.handle, s.events = {};
                for (i in l)for (n = 0, r = l[i].length; r > n; n++)x.event.add(t, i, l[i][n])
            }
            L.hasData(e) && (a = L.access(e), u = x.extend({}, a), L.set(t, u))
        }
    }

    function mt(e, t) {
        var n = e.getElementsByTagName ? e.getElementsByTagName(t || "*") : e.querySelectorAll ? e.querySelectorAll(t || "*") : [];
        return t === undefined || t && x.nodeName(e, t) ? x.merge([e], n) : n
    }

    function yt(e, t) {
        var n = t.nodeName.toLowerCase();
        "input" === n && ot.test(e.type) ? t.checked = e.checked : ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
    }

    x.fn.extend({
        wrapAll: function (e) {
            var t;
            return x.isFunction(e) ? this.each(function (t) {
                x(this).wrapAll(e.call(this, t))
            }) : (this[0] && (t = x(e, this[0].ownerDocument).eq(0).clone(!0), this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                var e = this;
                while (e.firstElementChild)e = e.firstElementChild;
                return e
            }).append(this)), this)
        }, wrapInner: function (e) {
            return x.isFunction(e) ? this.each(function (t) {
                x(this).wrapInner(e.call(this, t))
            }) : this.each(function () {
                var t = x(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        }, wrap: function (e) {
            var t = x.isFunction(e);
            return this.each(function (n) {
                x(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function () {
            return this.parent().each(function () {
                x.nodeName(this, "body") || x(this).replaceWith(this.childNodes)
            }).end()
        }
    });
    var vt, xt, bt = /^(none|table(?!-c[ea]).+)/, wt = /^margin/, Tt = RegExp("^(" + b + ")(.*)$", "i"), Ct = RegExp("^(" + b + ")(?!px)[a-z%]+$", "i"), kt = RegExp("^([+-])=(" + b + ")", "i"), Nt = {BODY: "block"}, Et = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, St = {
        letterSpacing: 0,
        fontWeight: 400
    }, jt = ["Top", "Right", "Bottom", "Left"], Dt = ["Webkit", "O", "Moz", "ms"];

    function At(e, t) {
        if (t in e)return t;
        var n = t.charAt(0).toUpperCase() + t.slice(1), r = t, i = Dt.length;
        while (i--)if (t = Dt[i] + n, t in e)return t;
        return r
    }

    function Lt(e, t) {
        return e = t || e, "none" === x.css(e, "display") || !x.contains(e.ownerDocument, e)
    }

    function qt(t) {
        return e.getComputedStyle(t, null)
    }

    function Ht(e, t) {
        var n, r, i, o = [], s = 0, a = e.length;
        for (; a > s; s++)r = e[s], r.style && (o[s] = q.get(r, "olddisplay"), n = r.style.display, t ? (o[s] || "none" !== n || (r.style.display = ""), "" === r.style.display && Lt(r) && (o[s] = q.access(r, "olddisplay", Rt(r.nodeName)))) : o[s] || (i = Lt(r), (n && "none" !== n || !i) && q.set(r, "olddisplay", i ? n : x.css(r, "display"))));
        for (s = 0; a > s; s++)r = e[s], r.style && (t && "none" !== r.style.display && "" !== r.style.display || (r.style.display = t ? o[s] || "" : "none"));
        return e
    }

    x.fn.extend({
        css: function (e, t) {
            return x.access(this, function (e, t, n) {
                var r, i, o = {}, s = 0;
                if (x.isArray(t)) {
                    for (r = qt(e), i = t.length; i > s; s++)o[t[s]] = x.css(e, t[s], !1, r);
                    return o
                }
                return n !== undefined ? x.style(e, t, n) : x.css(e, t)
            }, e, t, arguments.length > 1)
        }, show: function () {
            return Ht(this, !0)
        }, hide: function () {
            return Ht(this)
        }, toggle: function (e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function () {
                Lt(this) ? x(this).show() : x(this).hide()
            })
        }
    }), x.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = vt(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {"float": "cssFloat"},
        style: function (e, t, n, r) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var i, o, s, a = x.camelCase(t), u = e.style;
                return t = x.cssProps[a] || (x.cssProps[a] = At(u, a)), s = x.cssHooks[t] || x.cssHooks[a], n === undefined ? s && "get"in s && (i = s.get(e, !1, r)) !== undefined ? i : u[t] : (o = typeof n, "string" === o && (i = kt.exec(n)) && (n = (i[1] + 1) * i[2] + parseFloat(x.css(e, t)), o = "number"), null == n || "number" === o && isNaN(n) || ("number" !== o || x.cssNumber[a] || (n += "px"), x.support.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (u[t] = "inherit"), s && "set"in s && (n = s.set(e, n, r)) === undefined || (u[t] = n)), undefined)
            }
        },
        css: function (e, t, n, r) {
            var i, o, s, a = x.camelCase(t);
            return t = x.cssProps[a] || (x.cssProps[a] = At(e.style, a)), s = x.cssHooks[t] || x.cssHooks[a], s && "get"in s && (i = s.get(e, !0, n)), i === undefined && (i = vt(e, t, r)), "normal" === i && t in St && (i = St[t]), "" === n || n ? (o = parseFloat(i), n === !0 || x.isNumeric(o) ? o || 0 : i) : i
        }
    }), vt = function (e, t, n) {
        var r, i, o, s = n || qt(e), a = s ? s.getPropertyValue(t) || s[t] : undefined, u = e.style;
        return s && ("" !== a || x.contains(e.ownerDocument, e) || (a = x.style(e, t)), Ct.test(a) && wt.test(t) && (r = u.width, i = u.minWidth, o = u.maxWidth, u.minWidth = u.maxWidth = u.width = a, a = s.width, u.width = r, u.minWidth = i, u.maxWidth = o)), a
    };
    function Ot(e, t, n) {
        var r = Tt.exec(t);
        return r ? Math.max(0, r[1] - (n || 0)) + (r[2] || "px") : t
    }

    function Ft(e, t, n, r, i) {
        var o = n === (r ? "border" : "content") ? 4 : "width" === t ? 1 : 0, s = 0;
        for (; 4 > o; o += 2)"margin" === n && (s += x.css(e, n + jt[o], !0, i)), r ? ("content" === n && (s -= x.css(e, "padding" + jt[o], !0, i)), "margin" !== n && (s -= x.css(e, "border" + jt[o] + "Width", !0, i))) : (s += x.css(e, "padding" + jt[o], !0, i), "padding" !== n && (s += x.css(e, "border" + jt[o] + "Width", !0, i)));
        return s
    }

    function Pt(e, t, n) {
        var r = !0, i = "width" === t ? e.offsetWidth : e.offsetHeight, o = qt(e), s = x.support.boxSizing && "border-box" === x.css(e, "boxSizing", !1, o);
        if (0 >= i || null == i) {
            if (i = vt(e, t, o), (0 > i || null == i) && (i = e.style[t]), Ct.test(i))return i;
            r = s && (x.support.boxSizingReliable || i === e.style[t]), i = parseFloat(i) || 0
        }
        return i + Ft(e, t, n || (s ? "border" : "content"), r, o) + "px"
    }

    function Rt(e) {
        var t = o, n = Nt[e];
        return n || (n = Mt(e, t), "none" !== n && n || (xt = (xt || x("<iframe frameborder='0' width='0' height='0'/>").css("cssText", "display:block !important")).appendTo(t.documentElement), t = (xt[0].contentWindow || xt[0].contentDocument).document, t.write("<!doctype html><html><body>"), t.close(), n = Mt(e, t), xt.detach()), Nt[e] = n), n
    }

    function Mt(e, t) {
        var n = x(t.createElement(e)).appendTo(t.body), r = x.css(n[0], "display");
        return n.remove(), r
    }

    x.each(["height", "width"], function (e, t) {
        x.cssHooks[t] = {
            get: function (e, n, r) {
                return n ? 0 === e.offsetWidth && bt.test(x.css(e, "display")) ? x.swap(e, Et, function () {
                    return Pt(e, t, r)
                }) : Pt(e, t, r) : undefined
            }, set: function (e, n, r) {
                var i = r && qt(e);
                return Ot(e, n, r ? Ft(e, t, r, x.support.boxSizing && "border-box" === x.css(e, "boxSizing", !1, i), i) : 0)
            }
        }
    }), x(function () {
        x.support.reliableMarginRight || (x.cssHooks.marginRight = {
            get: function (e, t) {
                return t ? x.swap(e, {display: "inline-block"}, vt, [e, "marginRight"]) : undefined
            }
        }), !x.support.pixelPosition && x.fn.position && x.each(["top", "left"], function (e, t) {
            x.cssHooks[t] = {
                get: function (e, n) {
                    return n ? (n = vt(e, t), Ct.test(n) ? x(e).position()[t] + "px" : n) : undefined
                }
            }
        })
    }), x.expr && x.expr.filters && (x.expr.filters.hidden = function (e) {
        return 0 >= e.offsetWidth && 0 >= e.offsetHeight
    }, x.expr.filters.visible = function (e) {
        return !x.expr.filters.hidden(e)
    }), x.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        x.cssHooks[e + t] = {
            expand: function (n) {
                var r = 0, i = {}, o = "string" == typeof n ? n.split(" ") : [n];
                for (; 4 > r; r++)i[e + jt[r] + t] = o[r] || o[r - 2] || o[0];
                return i
            }
        }, wt.test(e) || (x.cssHooks[e + t].set = Ot)
    });
    var Wt = /%20/g, $t = /\[\]$/, Bt = /\r?\n/g, It = /^(?:submit|button|image|reset|file)$/i, zt = /^(?:input|select|textarea|keygen)/i;
    x.fn.extend({
        serialize: function () {
            return x.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                var e = x.prop(this, "elements");
                return e ? x.makeArray(e) : this
            }).filter(function () {
                var e = this.type;
                return this.name && !x(this).is(":disabled") && zt.test(this.nodeName) && !It.test(e) && (this.checked || !ot.test(e))
            }).map(function (e, t) {
                var n = x(this).val();
                return null == n ? null : x.isArray(n) ? x.map(n, function (e) {
                    return {name: t.name, value: e.replace(Bt, "\r\n")}
                }) : {name: t.name, value: n.replace(Bt, "\r\n")}
            }).get()
        }
    }), x.param = function (e, t) {
        var n, r = [], i = function (e, t) {
            t = x.isFunction(t) ? t() : null == t ? "" : t, r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
        };
        if (t === undefined && (t = x.ajaxSettings && x.ajaxSettings.traditional), x.isArray(e) || e.jquery && !x.isPlainObject(e))x.each(e, function () {
            i(this.name, this.value)
        }); else for (n in e)_t(n, e[n], t, i);
        return r.join("&").replace(Wt, "+")
    };
    function _t(e, t, n, r) {
        var i;
        if (x.isArray(t))x.each(t, function (t, i) {
            n || $t.test(e) ? r(e, i) : _t(e + "[" + ("object" == typeof i ? t : "") + "]", i, n, r)
        }); else if (n || "object" !== x.type(t))r(e, t); else for (i in t)_t(e + "[" + i + "]", t[i], n, r)
    }

    x.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        x.fn[t] = function (e, n) {
            return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), x.fn.extend({
        hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }, bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, delegate: function (e, t, n, r) {
            return this.on(t, e, n, r)
        }, undelegate: function (e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var Xt, Ut, Yt = x.now(), Vt = /\?/, Gt = /#.*$/, Jt = /([?&])_=[^&]*/, Qt = /^(.*?):[ \t]*([^\r\n]*)$/gm, Kt = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, Zt = /^(?:GET|HEAD)$/, en = /^\/\//, tn = /^([\w.+-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/, nn = x.fn.load, rn = {}, on = {}, sn = "*/".concat("*");
    try {
        Ut = i.href
    } catch (an) {
        Ut = o.createElement("a"), Ut.href = "", Ut = Ut.href
    }
    Xt = tn.exec(Ut.toLowerCase()) || [];
    function un(e) {
        return function (t, n) {
            "string" != typeof t && (n = t, t = "*");
            var r, i = 0, o = t.toLowerCase().match(w) || [];
            if (x.isFunction(n))while (r = o[i++])"+" === r[0] ? (r = r.slice(1) || "*", (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n)
        }
    }

    function ln(e, t, n, r) {
        var i = {}, o = e === on;

        function s(a) {
            var u;
            return i[a] = !0, x.each(e[a] || [], function (e, a) {
                var l = a(t, n, r);
                return "string" != typeof l || o || i[l] ? o ? !(u = l) : undefined : (t.dataTypes.unshift(l), s(l), !1)
            }), u
        }

        return s(t.dataTypes[0]) || !i["*"] && s("*")
    }

    function cn(e, t) {
        var n, r, i = x.ajaxSettings.flatOptions || {};
        for (n in t)t[n] !== undefined && ((i[n] ? e : r || (r = {}))[n] = t[n]);
        return r && x.extend(!0, e, r), e
    }

    x.fn.load = function (e, t, n) {
        if ("string" != typeof e && nn)return nn.apply(this, arguments);
        var r, i, o, s = this, a = e.indexOf(" ");
        return a >= 0 && (r = e.slice(a), e = e.slice(0, a)), x.isFunction(t) ? (n = t, t = undefined) : t && "object" == typeof t && (i = "POST"), s.length > 0 && x.ajax({
            url: e,
            type: i,
            dataType: "html",
            data: t
        }).done(function (e) {
            o = arguments, s.html(r ? x("<div>").append(x.parseHTML(e)).find(r) : e)
        }).complete(n && function (e, t) {
            s.each(n, o || [e.responseText, t, e])
        }), this
    }, x.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (e, t) {
        x.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), x.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Ut,
            type: "GET",
            isLocal: Kt.test(Xt[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": sn,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {xml: /xml/, html: /html/, json: /json/},
            responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
            converters: {"* text": String, "text html": !0, "text json": x.parseJSON, "text xml": x.parseXML},
            flatOptions: {url: !0, context: !0}
        },
        ajaxSetup: function (e, t) {
            return t ? cn(cn(e, x.ajaxSettings), t) : cn(x.ajaxSettings, e)
        },
        ajaxPrefilter: un(rn),
        ajaxTransport: un(on),
        ajax: function (e, t) {
            "object" == typeof e && (t = e, e = undefined), t = t || {};
            var n, r, i, o, s, a, u, l, c = x.ajaxSetup({}, t), p = c.context || c, f = c.context && (p.nodeType || p.jquery) ? x(p) : x.event, h = x.Deferred(), d = x.Callbacks("once memory"), g = c.statusCode || {}, m = {}, y = {}, v = 0, b = "canceled", T = {
                readyState: 0,
                getResponseHeader: function (e) {
                    var t;
                    if (2 === v) {
                        if (!o) {
                            o = {};
                            while (t = Qt.exec(i))o[t[1].toLowerCase()] = t[2]
                        }
                        t = o[e.toLowerCase()]
                    }
                    return null == t ? null : t
                },
                getAllResponseHeaders: function () {
                    return 2 === v ? i : null
                },
                setRequestHeader: function (e, t) {
                    var n = e.toLowerCase();
                    return v || (e = y[n] = y[n] || e, m[e] = t), this
                },
                overrideMimeType: function (e) {
                    return v || (c.mimeType = e), this
                },
                statusCode: function (e) {
                    var t;
                    if (e)if (2 > v)for (t in e)g[t] = [g[t], e[t]]; else T.always(e[T.status]);
                    return this
                },
                abort: function (e) {
                    var t = e || b;
                    return n && n.abort(t), k(0, t), this
                }
            };
            if (h.promise(T).complete = d.add, T.success = T.done, T.error = T.fail, c.url = ((e || c.url || Ut) + "").replace(Gt, "").replace(en, Xt[1] + "//"), c.type = t.method || t.type || c.method || c.type, c.dataTypes = x.trim(c.dataType || "*").toLowerCase().match(w) || [""], null == c.crossDomain && (a = tn.exec(c.url.toLowerCase()), c.crossDomain = !(!a || a[1] === Xt[1] && a[2] === Xt[2] && (a[3] || ("http:" === a[1] ? "80" : "443")) === (Xt[3] || ("http:" === Xt[1] ? "80" : "443")))), c.data && c.processData && "string" != typeof c.data && (c.data = x.param(c.data, c.traditional)), ln(rn, c, t, T), 2 === v)return T;
            u = c.global, u && 0 === x.active++ && x.event.trigger("ajaxStart"), c.type = c.type.toUpperCase(), c.hasContent = !Zt.test(c.type), r = c.url, c.hasContent || (c.data && (r = c.url += (Vt.test(r) ? "&" : "?") + c.data, delete c.data), c.cache === !1 && (c.url = Jt.test(r) ? r.replace(Jt, "$1_=" + Yt++) : r + (Vt.test(r) ? "&" : "?") + "_=" + Yt++)), c.ifModified && (x.lastModified[r] && T.setRequestHeader("If-Modified-Since", x.lastModified[r]), x.etag[r] && T.setRequestHeader("If-None-Match", x.etag[r])), (c.data && c.hasContent && c.contentType !== !1 || t.contentType) && T.setRequestHeader("Content-Type", c.contentType), T.setRequestHeader("Accept", c.dataTypes[0] && c.accepts[c.dataTypes[0]] ? c.accepts[c.dataTypes[0]] + ("*" !== c.dataTypes[0] ? ", " + sn + "; q=0.01" : "") : c.accepts["*"]);
            for (l in c.headers)T.setRequestHeader(l, c.headers[l]);
            if (c.beforeSend && (c.beforeSend.call(p, T, c) === !1 || 2 === v))return T.abort();
            b = "abort";
            for (l in{success: 1, error: 1, complete: 1})T[l](c[l]);
            if (n = ln(on, c, t, T)) {
                T.readyState = 1, u && f.trigger("ajaxSend", [T, c]), c.async && c.timeout > 0 && (s = setTimeout(function () {
                    T.abort("timeout")
                }, c.timeout));
                try {
                    v = 1, n.send(m, k)
                } catch (C) {
                    if (!(2 > v))throw C;
                    k(-1, C)
                }
            } else k(-1, "No Transport");
            function k(e, t, o, a) {
                var l, m, y, b, w, C = t;
                2 !== v && (v = 2, s && clearTimeout(s), n = undefined, i = a || "", T.readyState = e > 0 ? 4 : 0, l = e >= 200 && 300 > e || 304 === e, o && (b = pn(c, T, o)), b = fn(c, b, T, l), l ? (c.ifModified && (w = T.getResponseHeader("Last-Modified"), w && (x.lastModified[r] = w), w = T.getResponseHeader("etag"), w && (x.etag[r] = w)), 204 === e || "HEAD" === c.type ? C = "nocontent" : 304 === e ? C = "notmodified" : (C = b.state, m = b.data, y = b.error, l = !y)) : (y = C, (e || !C) && (C = "error", 0 > e && (e = 0))), T.status = e, T.statusText = (t || C) + "", l ? h.resolveWith(p, [m, C, T]) : h.rejectWith(p, [T, C, y]), T.statusCode(g), g = undefined, u && f.trigger(l ? "ajaxSuccess" : "ajaxError", [T, c, l ? m : y]), d.fireWith(p, [T, C]), u && (f.trigger("ajaxComplete", [T, c]), --x.active || x.event.trigger("ajaxStop")))
            }

            return T
        },
        getJSON: function (e, t, n) {
            return x.get(e, t, n, "json")
        },
        getScript: function (e, t) {
            return x.get(e, undefined, t, "script")
        }
    }), x.each(["get", "post"], function (e, t) {
        x[t] = function (e, n, r, i) {
            return x.isFunction(n) && (i = i || r, r = n, n = undefined), x.ajax({
                url: e,
                type: t,
                dataType: i,
                data: n,
                success: r
            })
        }
    });
    function pn(e, t, n) {
        var r, i, o, s, a = e.contents, u = e.dataTypes;
        while ("*" === u[0])u.shift(), r === undefined && (r = e.mimeType || t.getResponseHeader("Content-Type"));
        if (r)for (i in a)if (a[i] && a[i].test(r)) {
            u.unshift(i);
            break
        }
        if (u[0]in n)o = u[0]; else {
            for (i in n) {
                if (!u[0] || e.converters[i + " " + u[0]]) {
                    o = i;
                    break
                }
                s || (s = i)
            }
            o = o || s
        }
        return o ? (o !== u[0] && u.unshift(o), n[o]) : undefined
    }

    function fn(e, t, n, r) {
        var i, o, s, a, u, l = {}, c = e.dataTypes.slice();
        if (c[1])for (s in e.converters)l[s.toLowerCase()] = e.converters[s];
        o = c.shift();
        while (o)if (e.responseFields[o] && (n[e.responseFields[o]] = t), !u && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)), u = o, o = c.shift())if ("*" === o)o = u; else if ("*" !== u && u !== o) {
            if (s = l[u + " " + o] || l["* " + o], !s)for (i in l)if (a = i.split(" "), a[1] === o && (s = l[u + " " + a[0]] || l["* " + a[0]])) {
                s === !0 ? s = l[i] : l[i] !== !0 && (o = a[0], c.unshift(a[1]));
                break
            }
            if (s !== !0)if (s && e["throws"])t = s(t); else try {
                t = s(t)
            } catch (p) {
                return {state: "parsererror", error: s ? p : "No conversion from " + u + " to " + o}
            }
        }
        return {state: "success", data: t}
    }

    x.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /(?:java|ecma)script/},
        converters: {
            "text script": function (e) {
                return x.globalEval(e), e
            }
        }
    }), x.ajaxPrefilter("script", function (e) {
        e.cache === undefined && (e.cache = !1), e.crossDomain && (e.type = "GET")
    }), x.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var t, n;
            return {
                send: function (r, i) {
                    t = x("<script>").prop({
                        async: !0,
                        charset: e.scriptCharset,
                        src: e.url
                    }).on("load error", n = function (e) {
                        t.remove(), n = null, e && i("error" === e.type ? 404 : 200, e.type)
                    }), o.head.appendChild(t[0])
                }, abort: function () {
                    n && n()
                }
            }
        }
    });
    var hn = [], dn = /(=)\?(?=&|$)|\?\?/;
    x.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            var e = hn.pop() || x.expando + "_" + Yt++;
            return this[e] = !0, e
        }
    }), x.ajaxPrefilter("json jsonp", function (t, n, r) {
        var i, o, s, a = t.jsonp !== !1 && (dn.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && dn.test(t.data) && "data");
        return a || "jsonp" === t.dataTypes[0] ? (i = t.jsonpCallback = x.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, a ? t[a] = t[a].replace(dn, "$1" + i) : t.jsonp !== !1 && (t.url += (Vt.test(t.url) ? "&" : "?") + t.jsonp + "=" + i), t.converters["script json"] = function () {
            return s || x.error(i + " was not called"), s[0]
        }, t.dataTypes[0] = "json", o = e[i], e[i] = function () {
            s = arguments
        }, r.always(function () {
            e[i] = o, t[i] && (t.jsonpCallback = n.jsonpCallback, hn.push(i)), s && x.isFunction(o) && o(s[0]), s = o = undefined
        }), "script") : undefined
    }), x.ajaxSettings.xhr = function () {
        try {
            return new XMLHttpRequest
        } catch (e) {
        }
    };
    var gn = x.ajaxSettings.xhr(), mn = {0: 200, 1223: 204}, yn = 0, vn = {};
    e.ActiveXObject && x(e).on("unload", function () {
        for (var e in vn)vn[e]();
        vn = undefined
    }), x.support.cors = !!gn && "withCredentials"in gn, x.support.ajax = gn = !!gn, x.ajaxTransport(function (e) {
        var t;
        return x.support.cors || gn && !e.crossDomain ? {
            send: function (n, r) {
                var i, o, s = e.xhr();
                if (s.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)for (i in e.xhrFields)s[i] = e.xhrFields[i];
                e.mimeType && s.overrideMimeType && s.overrideMimeType(e.mimeType), e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest");
                for (i in n)s.setRequestHeader(i, n[i]);
                t = function (e) {
                    return function () {
                        t && (delete vn[o], t = s.onload = s.onerror = null, "abort" === e ? s.abort() : "error" === e ? r(s.status || 404, s.statusText) : r(mn[s.status] || s.status, s.statusText, "string" == typeof s.responseText ? {text: s.responseText} : undefined, s.getAllResponseHeaders()))
                    }
                }, s.onload = t(), s.onerror = t("error"), t = vn[o = yn++] = t("abort"), s.send(e.hasContent && e.data || null)
            }, abort: function () {
                t && t()
            }
        } : undefined
    });
    var xn, bn, wn = /^(?:toggle|show|hide)$/, Tn = RegExp("^(?:([+-])=|)(" + b + ")([a-z%]*)$", "i"), Cn = /queueHooks$/, kn = [An], Nn = {
        "*": [function (e, t) {
            var n = this.createTween(e, t), r = n.cur(), i = Tn.exec(t), o = i && i[3] || (x.cssNumber[e] ? "" : "px"), s = (x.cssNumber[e] || "px" !== o && +r) && Tn.exec(x.css(n.elem, e)), a = 1, u = 20;
            if (s && s[3] !== o) {
                o = o || s[3], i = i || [], s = +r || 1;
                do a = a || ".5", s /= a, x.style(n.elem, e, s + o); while (a !== (a = n.cur() / r) && 1 !== a && --u)
            }
            return i && (s = n.start = +s || +r || 0, n.unit = o, n.end = i[1] ? s + (i[1] + 1) * i[2] : +i[2]), n
        }]
    };

    function En() {
        return setTimeout(function () {
            xn = undefined
        }), xn = x.now()
    }

    function Sn(e, t, n) {
        var r, i = (Nn[t] || []).concat(Nn["*"]), o = 0, s = i.length;
        for (; s > o; o++)if (r = i[o].call(n, t, e))return r
    }

    function jn(e, t, n) {
        var r, i, o = 0, s = kn.length, a = x.Deferred().always(function () {
            delete u.elem
        }), u = function () {
            if (i)return !1;
            var t = xn || En(), n = Math.max(0, l.startTime + l.duration - t), r = n / l.duration || 0, o = 1 - r, s = 0, u = l.tweens.length;
            for (; u > s; s++)l.tweens[s].run(o);
            return a.notifyWith(e, [l, o, n]), 1 > o && u ? n : (a.resolveWith(e, [l]), !1)
        }, l = a.promise({
            elem: e,
            props: x.extend({}, t),
            opts: x.extend(!0, {specialEasing: {}}, n),
            originalProperties: t,
            originalOptions: n,
            startTime: xn || En(),
            duration: n.duration,
            tweens: [],
            createTween: function (t, n) {
                var r = x.Tween(e, l.opts, t, n, l.opts.specialEasing[t] || l.opts.easing);
                return l.tweens.push(r), r
            },
            stop: function (t) {
                var n = 0, r = t ? l.tweens.length : 0;
                if (i)return this;
                for (i = !0; r > n; n++)l.tweens[n].run(1);
                return t ? a.resolveWith(e, [l, t]) : a.rejectWith(e, [l, t]), this
            }
        }), c = l.props;
        for (Dn(c, l.opts.specialEasing); s > o; o++)if (r = kn[o].call(l, e, c, l.opts))return r;
        return x.map(c, Sn, l), x.isFunction(l.opts.start) && l.opts.start.call(e, l), x.fx.timer(x.extend(u, {
            elem: e,
            anim: l,
            queue: l.opts.queue
        })), l.progress(l.opts.progress).done(l.opts.done, l.opts.complete).fail(l.opts.fail).always(l.opts.always)
    }

    function Dn(e, t) {
        var n, r, i, o, s;
        for (n in e)if (r = x.camelCase(n), i = t[r], o = e[n], x.isArray(o) && (i = o[1], o = e[n] = o[0]), n !== r && (e[r] = o, delete e[n]), s = x.cssHooks[r], s && "expand"in s) {
            o = s.expand(o), delete e[r];
            for (n in o)n in e || (e[n] = o[n], t[n] = i)
        } else t[r] = i
    }

    x.Animation = x.extend(jn, {
        tweener: function (e, t) {
            x.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            var n, r = 0, i = e.length;
            for (; i > r; r++)n = e[r], Nn[n] = Nn[n] || [], Nn[n].unshift(t)
        }, prefilter: function (e, t) {
            t ? kn.unshift(e) : kn.push(e)
        }
    });
    function An(e, t, n) {
        var r, i, o, s, a, u, l = this, c = {}, p = e.style, f = e.nodeType && Lt(e), h = q.get(e, "fxshow");
        n.queue || (a = x._queueHooks(e, "fx"), null == a.unqueued && (a.unqueued = 0, u = a.empty.fire, a.empty.fire = function () {
            a.unqueued || u()
        }), a.unqueued++, l.always(function () {
            l.always(function () {
                a.unqueued--, x.queue(e, "fx").length || a.empty.fire()
            })
        })), 1 === e.nodeType && ("height"in t || "width"in t) && (n.overflow = [p.overflow, p.overflowX, p.overflowY], "inline" === x.css(e, "display") && "none" === x.css(e, "float") && (p.display = "inline-block")), n.overflow && (p.overflow = "hidden", l.always(function () {
            p.overflow = n.overflow[0], p.overflowX = n.overflow[1], p.overflowY = n.overflow[2]
        }));
        for (r in t)if (i = t[r], wn.exec(i)) {
            if (delete t[r], o = o || "toggle" === i, i === (f ? "hide" : "show")) {
                if ("show" !== i || !h || h[r] === undefined)continue;
                f = !0
            }
            c[r] = h && h[r] || x.style(e, r)
        }
        if (!x.isEmptyObject(c)) {
            h ? "hidden"in h && (f = h.hidden) : h = q.access(e, "fxshow", {}), o && (h.hidden = !f), f ? x(e).show() : l.done(function () {
                x(e).hide()
            }), l.done(function () {
                var t;
                q.remove(e, "fxshow");
                for (t in c)x.style(e, t, c[t])
            });
            for (r in c)s = Sn(f ? h[r] : 0, r, l), r in h || (h[r] = s.start, f && (s.end = s.start, s.start = "width" === r || "height" === r ? 1 : 0))
        }
    }

    function Ln(e, t, n, r, i) {
        return new Ln.prototype.init(e, t, n, r, i)
    }

    x.Tween = Ln, Ln.prototype = {
        constructor: Ln, init: function (e, t, n, r, i, o) {
            this.elem = e, this.prop = n, this.easing = i || "swing", this.options = t, this.start = this.now = this.cur(), this.end = r, this.unit = o || (x.cssNumber[n] ? "" : "px")
        }, cur: function () {
            var e = Ln.propHooks[this.prop];
            return e && e.get ? e.get(this) : Ln.propHooks._default.get(this)
        }, run: function (e) {
            var t, n = Ln.propHooks[this.prop];
            return this.pos = t = this.options.duration ? x.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : Ln.propHooks._default.set(this), this
        }
    }, Ln.prototype.init.prototype = Ln.prototype, Ln.propHooks = {
        _default: {
            get: function (e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = x.css(e.elem, e.prop, ""), t && "auto" !== t ? t : 0) : e.elem[e.prop]
            }, set: function (e) {
                x.fx.step[e.prop] ? x.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[x.cssProps[e.prop]] || x.cssHooks[e.prop]) ? x.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    }, Ln.propHooks.scrollTop = Ln.propHooks.scrollLeft = {
        set: function (e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, x.each(["toggle", "show", "hide"], function (e, t) {
        var n = x.fn[t];
        x.fn[t] = function (e, r, i) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(qn(t, !0), e, r, i)
        }
    }), x.fn.extend({
        fadeTo: function (e, t, n, r) {
            return this.filter(Lt).css("opacity", 0).show().end().animate({opacity: t}, e, n, r)
        }, animate: function (e, t, n, r) {
            var i = x.isEmptyObject(e), o = x.speed(t, n, r), s = function () {
                var t = jn(this, x.extend({}, e), o);
                (i || q.get(this, "finish")) && t.stop(!0)
            };
            return s.finish = s, i || o.queue === !1 ? this.each(s) : this.queue(o.queue, s)
        }, stop: function (e, t, n) {
            var r = function (e) {
                var t = e.stop;
                delete e.stop, t(n)
            };
            return "string" != typeof e && (n = t, t = e, e = undefined), t && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                var t = !0, i = null != e && e + "queueHooks", o = x.timers, s = q.get(this);
                if (i)s[i] && s[i].stop && r(s[i]); else for (i in s)s[i] && s[i].stop && Cn.test(i) && r(s[i]);
                for (i = o.length; i--;)o[i].elem !== this || null != e && o[i].queue !== e || (o[i].anim.stop(n), t = !1, o.splice(i, 1));
                (t || !n) && x.dequeue(this, e)
            })
        }, finish: function (e) {
            return e !== !1 && (e = e || "fx"), this.each(function () {
                var t, n = q.get(this), r = n[e + "queue"], i = n[e + "queueHooks"], o = x.timers, s = r ? r.length : 0;
                for (n.finish = !0, x.queue(this, e, []), i && i.stop && i.stop.call(this, !0), t = o.length; t--;)o[t].elem === this && o[t].queue === e && (o[t].anim.stop(!0), o.splice(t, 1));
                for (t = 0; s > t; t++)r[t] && r[t].finish && r[t].finish.call(this);
                delete n.finish
            })
        }
    });
    function qn(e, t) {
        var n, r = {height: e}, i = 0;
        for (t = t ? 1 : 0; 4 > i; i += 2 - t)n = jt[i], r["margin" + n] = r["padding" + n] = e;
        return t && (r.opacity = r.width = e), r
    }

    x.each({
        slideDown: qn("show"),
        slideUp: qn("hide"),
        slideToggle: qn("toggle"),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (e, t) {
        x.fn[e] = function (e, n, r) {
            return this.animate(t, e, n, r)
        }
    }), x.speed = function (e, t, n) {
        var r = e && "object" == typeof e ? x.extend({}, e) : {
            complete: n || !n && t || x.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !x.isFunction(t) && t
        };
        return r.duration = x.fx.off ? 0 : "number" == typeof r.duration ? r.duration : r.duration in x.fx.speeds ? x.fx.speeds[r.duration] : x.fx.speeds._default, (null == r.queue || r.queue === !0) && (r.queue = "fx"), r.old = r.complete, r.complete = function () {
            x.isFunction(r.old) && r.old.call(this), r.queue && x.dequeue(this, r.queue)
        }, r
    }, x.easing = {
        linear: function (e) {
            return e
        }, swing: function (e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }
    }, x.timers = [], x.fx = Ln.prototype.init, x.fx.tick = function () {
        var e, t = x.timers, n = 0;
        for (xn = x.now(); t.length > n; n++)e = t[n], e() || t[n] !== e || t.splice(n--, 1);
        t.length || x.fx.stop(), xn = undefined
    }, x.fx.timer = function (e) {
        e() && x.timers.push(e) && x.fx.start()
    }, x.fx.interval = 13, x.fx.start = function () {
        bn || (bn = setInterval(x.fx.tick, x.fx.interval))
    }, x.fx.stop = function () {
        clearInterval(bn), bn = null
    }, x.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    }, x.fx.step = {}, x.expr && x.expr.filters && (x.expr.filters.animated = function (e) {
        return x.grep(x.timers, function (t) {
            return e === t.elem
        }).length
    }), x.fn.offset = function (e) {
        if (arguments.length)return e === undefined ? this : this.each(function (t) {
            x.offset.setOffset(this, e, t)
        });
        var t, n, i = this[0], o = {top: 0, left: 0}, s = i && i.ownerDocument;
        if (s)return t = s.documentElement, x.contains(t, i) ? (typeof i.getBoundingClientRect !== r && (o = i.getBoundingClientRect()), n = Hn(s), {
            top: o.top + n.pageYOffset - t.clientTop,
            left: o.left + n.pageXOffset - t.clientLeft
        }) : o
    }, x.offset = {
        setOffset: function (e, t, n) {
            var r, i, o, s, a, u, l, c = x.css(e, "position"), p = x(e), f = {};
            "static" === c && (e.style.position = "relative"), a = p.offset(), o = x.css(e, "top"), u = x.css(e, "left"), l = ("absolute" === c || "fixed" === c) && (o + u).indexOf("auto") > -1, l ? (r = p.position(), s = r.top, i = r.left) : (s = parseFloat(o) || 0, i = parseFloat(u) || 0), x.isFunction(t) && (t = t.call(e, n, a)), null != t.top && (f.top = t.top - a.top + s), null != t.left && (f.left = t.left - a.left + i), "using"in t ? t.using.call(e, f) : p.css(f)
        }
    }, x.fn.extend({
        position: function () {
            if (this[0]) {
                var e, t, n = this[0], r = {top: 0, left: 0};
                return "fixed" === x.css(n, "position") ? t = n.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), x.nodeName(e[0], "html") || (r = e.offset()), r.top += x.css(e[0], "borderTopWidth", !0), r.left += x.css(e[0], "borderLeftWidth", !0)), {
                    top: t.top - r.top - x.css(n, "marginTop", !0),
                    left: t.left - r.left - x.css(n, "marginLeft", !0)
                }
            }
        }, offsetParent: function () {
            return this.map(function () {
                var e = this.offsetParent || s;
                while (e && !x.nodeName(e, "html") && "static" === x.css(e, "position"))e = e.offsetParent;
                return e || s
            })
        }
    }), x.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (t, n) {
        var r = "pageYOffset" === n;
        x.fn[t] = function (i) {
            return x.access(this, function (t, i, o) {
                var s = Hn(t);
                return o === undefined ? s ? s[n] : t[i] : (s ? s.scrollTo(r ? e.pageXOffset : o, r ? o : e.pageYOffset) : t[i] = o, undefined)
            }, t, i, arguments.length, null)
        }
    });
    function Hn(e) {
        return x.isWindow(e) ? e : 9 === e.nodeType && e.defaultView
    }

    x.each({Height: "height", Width: "width"}, function (e, t) {
        x.each({padding: "inner" + e, content: t, "": "outer" + e}, function (n, r) {
            x.fn[r] = function (r, i) {
                var o = arguments.length && (n || "boolean" != typeof r), s = n || (r === !0 || i === !0 ? "margin" : "border");
                return x.access(this, function (t, n, r) {
                    var i;
                    return x.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (i = t.documentElement, Math.max(t.body["scroll" + e], i["scroll" + e], t.body["offset" + e], i["offset" + e], i["client" + e])) : r === undefined ? x.css(t, n, s) : x.style(t, n, r, s)
                }, t, o ? r : undefined, o, null)
            }
        })
    }), x.fn.size = function () {
        return this.length
    }, x.fn.andSelf = x.fn.addBack, "object" == typeof module && module && "object" == typeof module.exports ? module.exports = x : "function" == typeof define && define.amd && define("jquery", [], function () {
        return x
    }), "object" == typeof e && "object" == typeof e.document && (e.jQuery = e.$ = x)
})(window);
// Avoid `console` errors in browsers that lack a console.
(function () {
    var method;
    var noop = function noop() {
    };
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

/* jwerty - Awesome handling of keyboard events */
(function (m, u) {
    function j(a, b) {
        return null === a ? "null" === b : void 0 === a ? "undefined" === b : a.is && a instanceof k ? "element" === b : 7 < Object.prototype.toString.call(a).toLowerCase().indexOf(b)
    }

    function p(a) {
        var b, d, f, e, i, c, g, l;
        if (a instanceof p)return a;
        j(a, "array") || (a = String(a).replace(/\s/g, "").toLowerCase().match(/(?:\+,|[^,])+/g));
        b = 0;
        for (d = a.length; b < d; ++b) {
            j(a[b], "array") || (a[b] = String(a[b]).match(/(?:\+\/|[^\/])+/g));
            c = [];
            for (f = a[b].length; f--;) {
                g = a[b][f];
                i = {
                    jwertyCombo: String(g), shiftKey: !1, ctrlKey: !1,
                    altKey: !1, metaKey: !1
                };
                j(g, "array") || (g = String(g).toLowerCase().match(/(?:(?:[^\+])+|\+\+|^\+$)/g));
                for (e = g.length; e--;)"++" === g[e] && (g[e] = "+"), g[e]in h.mods ? i[v[h.mods[g[e]]]] = !0 : g[e]in h.keys ? i.keyCode = h.keys[g[e]] : l = g[e].match(/^\[([^-]+\-?[^-]*)-([^-]+\-?[^-]*)\]$/);
                if (j(i.keyCode, "undefined"))if (l && l[1]in h.keys && l[2]in h.keys) {
                    l[2] = h.keys[l[2]];
                    l[1] = h.keys[l[1]];
                    for (e = l[1]; e < l[2]; ++e)c.push({
                        altKey: i.altKey,
                        shiftKey: i.shiftKey,
                        metaKey: i.metaKey,
                        ctrlKey: i.ctrlKey,
                        keyCode: e,
                        jwertyCombo: String(g)
                    });
                    i.keyCode = e
                } else i.keyCode = 0;
                c.push(i)
            }
            this[b] = c
        }
        this.length = b;
        return this
    }

    var q = m.document, k = m.jQuery || m.Zepto || m.ender || q, r, s;
    k === q ? (r = function (a, b) {
        return a ? k.querySelector(a, b || k) : k
    }, s = function (a, b) {
        a.addEventListener("keydown", b, !1)
    }, $f = function (a, b) {
        var d = document.createEvent("Event"), f;
        d.initEvent("keydown", !0, !0);
        for (f in b)d[f] = b[f];
        return (a || k).dispatchEvent(d)
    }) : (r = function (a, b) {
        return k(a || q, b)
    }, s = function (a, b) {
        k(a).bind("keydown.jwerty", b)
    }, $f = function (a, b) {
        k(a || q).trigger(k.Event("keydown",
            b))
    });
    var v = {16: "shiftKey", 17: "ctrlKey", 18: "altKey", 91: "metaKey"}, h = {
        mods: {
            "\u21e7": 16,
            shift: 16,
            "\u2303": 17,
            ctrl: 17,
            "\u2325": 18,
            alt: 18,
            option: 18,
            "\u2318": 91,
            meta: 91,
            cmd: 91,
            "super": 91,
            win: 91
        }, keys: {
            "\u232b": 8,
            backspace: 8,
            "\u21e5": 9,
            "\u21c6": 9,
            tab: 9,
            "\u21a9": 13,
            "return": 13,
            enter: 13,
            "\u2305": 13,
            pause: 19,
            "pause-break": 19,
            "\u21ea": 20,
            caps: 20,
            "caps-lock": 20,
            "\u238b": 27,
            escape: 27,
            esc: 27,
            space: 32,
            "\u2196": 33,
            pgup: 33,
            "page-up": 33,
            "\u2198": 34,
            pgdown: 34,
            "page-down": 34,
            "\u21df": 35,
            end: 35,
            "\u21de": 36,
            home: 36,
            ins: 45,
            insert: 45,
            del: 46,
            "delete": 46,
            "\u2190": 37,
            left: 37,
            "arrow-left": 37,
            "\u2191": 38,
            up: 38,
            "arrow-up": 38,
            "\u2192": 39,
            right: 39,
            "arrow-right": 39,
            "\u2193": 40,
            down: 40,
            "arrow-down": 40,
            "*": 106,
            star: 106,
            asterisk: 106,
            multiply: 106,
            "+": 107,
            plus: 107,
            "-": 109,
            subtract: 109,
            ";": 186,
            semicolon: 186,
            "=": 187,
            equals: 187,
            ",": 188,
            comma: 188,
            ".": 190,
            period: 190,
            "full-stop": 190,
            "/": 191,
            slash: 191,
            "forward-slash": 191,
            "`": 192,
            tick: 192,
            "back-quote": 192,
            "[": 219,
            "open-bracket": 219,
            "\\": 220,
            "back-slash": 220,
            "]": 221,
            "close-bracket": 221,
            "'": 222,
            quote: 222,
            apostraphe: 222
        }
    }, c = 95;
    for (n = 0; 106 > ++c;)h.keys["num-" + n] = c, ++n;
    c = 47;
    for (n = 0; 58 > ++c;)h.keys[n] = c, ++n;
    c = 111;
    for (n = 1; 136 > ++c;)h.keys["f" + n] = c, ++n;
    for (var c = 64; 91 > ++c;)h.keys[String.fromCharCode(c).toLowerCase()] = c;
    var t = u.jwerty = {
        event: function (a, b, d) {
            if (j(b, "boolean"))var f = b, b = function () {
                return f
            };
            var a = new p(a), e = 0, i = a.length - 1, c, g;
            return function (f) {
                (g = t.is(a, f, e)) ? e < i ? ++e : (c = b.call(d || this, f, g), !1 === c && f.preventDefault(), e = 0) : e = t.is(a, f) ? 1 : 0
            }
        }, is: function (a, b, d) {
            for (var a = new p(a),
                     a = a[d || 0], b = b.originalEvent || b, d = a.length, f = !1; d--;) {
                var f = a[d].jwertyCombo, e;
                for (e in a[d])"jwertyCombo" !== e && b[e] != a[d][e] && (f = !1);
                if (!1 !== f)break
            }
            return f
        }, key: function (a, b, d, f, e) {
            var c = j(d, "element") || j(d, "string") ? d : f, h = c === d ? m : d, d = c === d ? f : e;
            s(j(c, "element") ? c : r(c, d), t.event(a, b, h))
        }, fire: function (a, b, d, c) {
            a = new p(a);
            c = j(d, "number") ? d : c;
            $f(j(b, "element") ? b : r(b, d), a[c || 0][0])
        }, KEYS: h
    }
})(this, "undefined" !== typeof module && module.exports ? module.exports : this);

/* imagesLoaded */
(function (c, n) {
    var l = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
    c.fn.imagesLoaded = function (f) {
        function m() {
            var b = c(i), a = c(h);
            d && (h.length ? d.reject(e, b, a) : d.resolve(e));
            c.isFunction(f) && f.call(g, e, b, a)
        }

        function j(b, a) {
            b.src === l || -1 !== c.inArray(b, k) || (k.push(b), a ? h.push(b) : i.push(b), c.data(b, "imagesLoaded", {
                isBroken: a,
                src: b.src
            }), o && d.notifyWith(c(b), [a, e, c(i), c(h)]), e.length === k.length && (setTimeout(m), e.unbind(".imagesLoaded")))
        }

        var g = this, d = c.isFunction(c.Deferred) ? c.Deferred() :
            0, o = c.isFunction(d.notify), e = g.find("img").add(g.filter("img")), k = [], i = [], h = [];
        c.isPlainObject(f) && c.each(f, function (b, a) {
            if ("callback" === b)f = a; else if (d)d[b](a)
        });
        e.length ? e.bind("load.imagesLoaded error.imagesLoaded", function (b) {
            j(b.target, "error" === b.type)
        }).each(function (b, a) {
            var d = a.src, e = c.data(a, "imagesLoaded");
            if (e && e.src === d)j(a, e.isBroken); else if (a.complete && a.naturalWidth !== n)j(a, 0 === a.naturalWidth || 0 === a.naturalHeight); else if (a.readyState || a.complete)a.src = l, a.src = d
        }) : m();
        return d ? d.promise(g) :
            g
    }
})(jQuery);
window.CodeMirror = function () {
    "use strict";
    function w(a, c) {
        if (!(this instanceof w))return new w(a, c);
        this.options = c = c || {};
        for (var d in Xc)!c.hasOwnProperty(d) && Xc.hasOwnProperty(d) && (c[d] = Xc[d]);
        I(c);
        var e = "string" == typeof c.value ? 0 : c.value.first, f = this.display = x(a, e);
        f.wrapper.CodeMirror = this, F(this), c.autofocus && !o && Lb(this), this.state = {
            keyMaps: [],
            overlays: [],
            modeGen: 0,
            overwrite: !1,
            focused: !1,
            suppressEdits: !1,
            pasteIncoming: !1,
            draggingText: !1,
            highlight: new Te
        }, D(this), c.lineWrapping && (this.display.wrapper.className += " CodeMirror-wrap");
        var g = c.value;
        "string" == typeof g && (g = new ce(c.value, c.mode)), Db(this, ge)(this, g), b && setTimeout(bf(Kb, this, !0), 20), Nb(this);
        var h;
        try {
            h = document.activeElement == f.input
        } catch (i) {
        }
        h || c.autofocus && !o ? setTimeout(bf(ic, this), 20) : jc(this), Db(this, function () {
            for (var a in Wc)Wc.propertyIsEnumerable(a) && Wc[a](this, c[a], Zc);
            for (var b = 0; b < bd.length; ++b)bd[b](this)
        })()
    }

    function x(a, b) {
        var d = {}, f = d.input = gf("textarea", null, null, "position: absolute; padding: 0; width: 1px; height: 1em; outline: none; font-size: 4px;");
        return e ? f.style.width = "1000px" : f.setAttribute("wrap", "off"), n && (f.style.border = "1px solid black"), f.setAttribute("autocorrect", "off"), f.setAttribute("autocapitalize", "off"), f.setAttribute("spellcheck", "false"), d.inputDiv = gf("div", [f], null, "overflow: hidden; position: relative; width: 3px; height: 0px;"), d.scrollbarH = gf("div", [gf("div", null, null, "height: 1px")], "CodeMirror-hscrollbar"), d.scrollbarV = gf("div", [gf("div", null, null, "width: 1px")], "CodeMirror-vscrollbar"), d.scrollbarFiller = gf("div", null, "CodeMirror-scrollbar-filler"), d.gutterFiller = gf("div", null, "CodeMirror-gutter-filler"), d.lineDiv = gf("div", null, "CodeMirror-code"), d.selectionDiv = gf("div", null, null, "position: relative; z-index: 1"), d.cursor = gf("div", "\xa0", "CodeMirror-cursor"), d.otherCursor = gf("div", "\xa0", "CodeMirror-cursor CodeMirror-secondarycursor"), d.measure = gf("div", null, "CodeMirror-measure"), d.lineSpace = gf("div", [d.measure, d.selectionDiv, d.lineDiv, d.cursor, d.otherCursor], null, "position: relative; outline: none"), d.mover = gf("div", [gf("div", [d.lineSpace], "CodeMirror-lines")], null, "position: relative"), d.sizer = gf("div", [d.mover], "CodeMirror-sizer"), d.heightForcer = gf("div", null, null, "position: absolute; height: " + Re + "px; width: 1px;"), d.gutters = gf("div", null, "CodeMirror-gutters"), d.lineGutter = null, d.scroller = gf("div", [d.sizer, d.heightForcer, d.gutters], "CodeMirror-scroll"), d.scroller.setAttribute("tabIndex", "-1"), d.wrapper = gf("div", [d.inputDiv, d.scrollbarH, d.scrollbarV, d.scrollbarFiller, d.gutterFiller, d.scroller], "CodeMirror"), c && (d.gutters.style.zIndex = -1, d.scroller.style.paddingRight = 0), a.appendChild ? a.appendChild(d.wrapper) : a(d.wrapper), n && (f.style.width = "0px"), e || (d.scroller.draggable = !0), j ? (d.inputDiv.style.height = "1px", d.inputDiv.style.position = "absolute") : c && (d.scrollbarH.style.minWidth = d.scrollbarV.style.minWidth = "18px"), d.viewOffset = d.lastSizeC = 0, d.showingFrom = d.showingTo = b, d.lineNumWidth = d.lineNumInnerWidth = d.lineNumChars = null, d.prevInput = "", d.alignWidgets = !1, d.pollingFast = !1, d.poll = new Te, d.cachedCharWidth = d.cachedTextHeight = null, d.measureLineCache = [], d.measureLineCachePos = 0, d.inaccurateSelection = !1, d.maxLine = null, d.maxLineLength = 0, d.maxLineChanged = !1, d.wheelDX = d.wheelDY = d.wheelStartX = d.wheelStartY = null, d
    }

    function y(a) {
        a.doc.mode = w.getMode(a.options, a.doc.modeOption), a.doc.iter(function (a) {
            a.stateAfter && (a.stateAfter = null), a.styles && (a.styles = null)
        }), a.doc.frontier = a.doc.first, ab(a, 100), a.state.modeGen++, a.curOp && Gb(a)
    }

    function z(a) {
        a.options.lineWrapping ? (a.display.wrapper.className += " CodeMirror-wrap", a.display.sizer.style.minWidth = "") : (a.display.wrapper.className = a.display.wrapper.className.replace(" CodeMirror-wrap", ""), H(a)), B(a), Gb(a), nb(a), setTimeout(function () {
            J(a)
        }, 100)
    }

    function A(a) {
        var b = yb(a.display), c = a.options.lineWrapping, d = c && Math.max(5, a.display.scroller.clientWidth / zb(a.display) - 3);
        return function (e) {
            return Cd(a.doc, e) ? 0 : c ? (Math.ceil(e.text.length / d) || 1) * b : b
        }
    }

    function B(a) {
        var b = a.doc, c = A(a);
        b.iter(function (a) {
            var b = c(a);
            b != a.height && ke(a, b)
        })
    }

    function C(a) {
        var b = gd[a.options.keyMap], c = b.style;
        a.display.wrapper.className = a.display.wrapper.className.replace(/\s*cm-keymap-\S+/g, "") + (c ? " cm-keymap-" + c : ""), a.state.disableInput = b.disableInput
    }

    function D(a) {
        a.display.wrapper.className = a.display.wrapper.className.replace(/\s*cm-s-\S+/g, "") + a.options.theme.replace(/(^|\s)\s*/g, " cm-s-"), nb(a)
    }

    function E(a) {
        F(a), Gb(a), setTimeout(function () {
            L(a)
        }, 20)
    }

    function F(a) {
        var b = a.display.gutters, c = a.options.gutters;
        hf(b);
        for (var d = 0; d < c.length; ++d) {
            var e = c[d], f = b.appendChild(gf("div", null, "CodeMirror-gutter " + e));
            "CodeMirror-linenumbers" == e && (a.display.lineGutter = f, f.style.width = (a.display.lineNumWidth || 1) + "px")
        }
        b.style.display = d ? "" : "none"
    }

    function G(a, b) {
        if (0 == b.height)return 0;
        for (var d, c = b.text.length, e = b; d = zd(e);) {
            var f = d.find();
            e = he(a, f.from.line), c += f.from.ch - f.to.ch
        }
        for (e = b; d = Ad(e);) {
            var f = d.find();
            c -= e.text.length - f.from.ch, e = he(a, f.to.line), c += e.text.length - f.to.ch
        }
        return c
    }

    function H(a) {
        var b = a.display, c = a.doc;
        b.maxLine = he(c, c.first), b.maxLineLength = G(c, b.maxLine), b.maxLineChanged = !0, c.iter(function (a) {
            var d = G(c, a);
            d > b.maxLineLength && (b.maxLineLength = d, b.maxLine = a)
        })
    }

    function I(a) {
        for (var b = !1, c = 0; c < a.gutters.length; ++c)"CodeMirror-linenumbers" == a.gutters[c] && (a.lineNumbers ? b = !0 : a.gutters.splice(c--, 1));
        !b && a.lineNumbers && a.gutters.push("CodeMirror-linenumbers")
    }

    function J(a) {
        var b = a.display, c = a.doc.height, d = c + fb(b);
        b.sizer.style.minHeight = b.heightForcer.style.top = d + "px", b.gutters.style.height = Math.max(d, b.scroller.clientHeight - Re) + "px";
        var e = Math.max(d, b.scroller.scrollHeight), f = b.scroller.scrollWidth > b.scroller.clientWidth + 1, g = e > b.scroller.clientHeight + 1;
        g ? (b.scrollbarV.style.display = "block", b.scrollbarV.style.bottom = f ? pf(b.measure) + "px" : "0", b.scrollbarV.firstChild.style.height = e - b.scroller.clientHeight + b.scrollbarV.clientHeight + "px") : (b.scrollbarV.style.display = "", b.scrollbarV.firstChild.style.height = "0"), f ? (b.scrollbarH.style.display = "block", b.scrollbarH.style.right = g ? pf(b.measure) + "px" : "0", b.scrollbarH.firstChild.style.width = b.scroller.scrollWidth - b.scroller.clientWidth + b.scrollbarH.clientWidth + "px") : (b.scrollbarH.style.display = "", b.scrollbarH.firstChild.style.width = "0"), f && g ? (b.scrollbarFiller.style.display = "block", b.scrollbarFiller.style.height = b.scrollbarFiller.style.width = pf(b.measure) + "px") : b.scrollbarFiller.style.display = "", f && a.options.coverGutterNextToScrollbar && a.options.fixedGutter ? (b.gutterFiller.style.display = "block", b.gutterFiller.style.height = pf(b.measure) + "px", b.gutterFiller.style.width = b.gutters.offsetWidth + "px") : b.gutterFiller.style.display = "", k && 0 === pf(b.measure) && (b.scrollbarV.style.minWidth = b.scrollbarH.style.minHeight = l ? "18px" : "12px")
    }

    function K(a, b, c) {
        var d = a.scroller.scrollTop, e = a.wrapper.clientHeight;
        "number" == typeof c ? d = c : c && (d = c.top, e = c.bottom - c.top), d = Math.floor(d - eb(a));
        var f = Math.ceil(d + e);
        return {from: me(b, d), to: me(b, f)}
    }

    function L(a) {
        var b = a.display;
        if (b.alignWidgets || b.gutters.firstChild && a.options.fixedGutter) {
            for (var c = O(b) - b.scroller.scrollLeft + a.doc.scrollLeft, d = b.gutters.offsetWidth, e = c + "px", f = b.lineDiv.firstChild; f; f = f.nextSibling)if (f.alignable)for (var g = 0, h = f.alignable; g < h.length; ++g)h[g].style.left = e;
            a.options.fixedGutter && (b.gutters.style.left = c + d + "px")
        }
    }

    function M(a) {
        if (!a.options.lineNumbers)return !1;
        var b = a.doc, c = N(a.options, b.first + b.size - 1), d = a.display;
        if (c.length != d.lineNumChars) {
            var e = d.measure.appendChild(gf("div", [gf("div", c)], "CodeMirror-linenumber CodeMirror-gutter-elt")), f = e.firstChild.offsetWidth, g = e.offsetWidth - f;
            return d.lineGutter.style.width = "", d.lineNumInnerWidth = Math.max(f, d.lineGutter.offsetWidth - g), d.lineNumWidth = d.lineNumInnerWidth + g, d.lineNumChars = d.lineNumInnerWidth ? c.length : -1, d.lineGutter.style.width = d.lineNumWidth + "px", !0
        }
        return !1
    }

    function N(a, b) {
        return String(a.lineNumberFormatter(b + a.firstLineNumber))
    }

    function O(a) {
        return lf(a.scroller).left - lf(a.sizer).left
    }

    function P(a, b, c, d) {
        for (var g, e = a.display.showingFrom, f = a.display.showingTo, h = K(a.display, a.doc, c); Q(a, b, h, d) && (d = !1, g = !0, Y(a), J(a), c && (c = Math.min(a.display.scroller.scrollHeight - a.display.scroller.clientHeight, "number" == typeof c ? c : c.top)), h = K(a.display, a.doc, c), !(h.from >= a.display.showingFrom && h.to <= a.display.showingTo));)b = [];
        return g && (Me(a, "update", a), (a.display.showingFrom != e || a.display.showingTo != f) && Me(a, "viewportChange", a, a.display.showingFrom, a.display.showingTo)), g
    }

    function Q(a, b, c, d) {
        var e = a.display, f = a.doc;
        if (!e.wrapper.clientWidth)return e.showingFrom = e.showingTo = f.first, e.viewOffset = 0, void 0;
        if (!(!d && 0 == b.length && c.from > e.showingFrom && c.to < e.showingTo)) {
            M(a) && (b = [{from: f.first, to: f.first + f.size}]);
            var g = e.sizer.style.marginLeft = e.gutters.offsetWidth + "px";
            e.scrollbarH.style.left = a.options.fixedGutter ? g : "0";
            var h = 1 / 0;
            if (a.options.lineNumbers)for (var i = 0; i < b.length; ++i)b[i].diff && b[i].from < h && (h = b[i].from);
            var j = f.first + f.size, k = Math.max(c.from - a.options.viewportMargin, f.first), l = Math.min(j, c.to + a.options.viewportMargin);
            if (e.showingFrom < k && k - e.showingFrom < 20 && (k = Math.max(f.first, e.showingFrom)), e.showingTo > l && e.showingTo - l < 20 && (l = Math.min(j, e.showingTo)), v)for (k = le(Bd(f, he(f, k))); j > l && Cd(f, he(f, l));)++l;
            var m = [{from: Math.max(e.showingFrom, f.first), to: Math.min(e.showingTo, j)}];
            if (m = m[0].from >= m[0].to ? [] : T(m, b), v)for (var i = 0; i < m.length; ++i)for (var o, n = m[i]; o = Ad(he(f, n.to - 1));) {
                var p = o.find().from.line;
                if (!(p > n.from)) {
                    m.splice(i--, 1);
                    break
                }
                n.to = p
            }
            for (var q = 0, i = 0; i < m.length; ++i) {
                var n = m[i];
                n.from < k && (n.from = k), n.to > l && (n.to = l), n.from >= n.to ? m.splice(i--, 1) : q += n.to - n.from
            }
            if (!d && q == l - k && k == e.showingFrom && l == e.showingTo)return S(a), void 0;
            m.sort(function (a, b) {
                return a.from - b.from
            });
            try {
                var r = document.activeElement
            } catch (s) {
            }
            .7 * (l - k) > q && (e.lineDiv.style.display = "none"), V(a, k, l, m, h), e.lineDiv.style.display = "", r && document.activeElement != r && r.offsetHeight && r.focus();
            var t = k != e.showingFrom || l != e.showingTo || e.lastSizeC != e.wrapper.clientHeight;
            return t && (e.lastSizeC = e.wrapper.clientHeight, ab(a, 400)), e.showingFrom = k, e.showingTo = l, R(a), S(a), !0
        }
    }

    function R(a) {
        for (var f, b = a.display, d = b.lineDiv.offsetTop, e = b.lineDiv.firstChild; e; e = e.nextSibling)if (e.lineObj) {
            if (c) {
                var g = e.offsetTop + e.offsetHeight;
                f = g - d, d = g
            } else {
                var h = lf(e);
                f = h.bottom - h.top
            }
            var i = e.lineObj.height - f;
            if (2 > f && (f = yb(b)), i > .001 || -.001 > i) {
                ke(e.lineObj, f);
                var j = e.lineObj.widgets;
                if (j)for (var k = 0; k < j.length; ++k)j[k].height = j[k].node.offsetHeight
            }
        }
    }

    function S(a) {
        var b = a.display.viewOffset = ne(a, he(a.doc, a.display.showingFrom));
        a.display.mover.style.top = b + "px"
    }

    function T(a, b) {
        for (var c = 0, d = b.length || 0; d > c; ++c) {
            for (var e = b[c], f = [], g = e.diff || 0, h = 0, i = a.length; i > h; ++h) {
                var j = a[h];
                e.to <= j.from && e.diff ? f.push({
                    from: j.from + g,
                    to: j.to + g
                }) : e.to <= j.from || e.from >= j.to ? f.push(j) : (e.from > j.from && f.push({
                    from: j.from,
                    to: e.from
                }), e.to < j.to && f.push({from: e.to + g, to: j.to + g}))
            }
            a = f
        }
        return a
    }

    function U(a) {
        for (var b = a.display, c = {}, d = {}, e = b.gutters.firstChild, f = 0; e; e = e.nextSibling, ++f)c[a.options.gutters[f]] = e.offsetLeft, d[a.options.gutters[f]] = e.offsetWidth;
        return {
            fixedPos: O(b),
            gutterTotalWidth: b.gutters.offsetWidth,
            gutterLeft: c,
            gutterWidth: d,
            wrapperWidth: b.wrapper.clientWidth
        }
    }

    function V(a, b, c, d, f) {
        function l(b) {
            var c = b.nextSibling;
            return e && p && a.display.currentWheelTarget == b ? (b.style.display = "none", b.lineObj = null) : b.parentNode.removeChild(b), c
        }

        var g = U(a), h = a.display, i = a.options.lineNumbers;
        d.length || e && a.display.currentWheelTarget || hf(h.lineDiv);
        var j = h.lineDiv, k = j.firstChild, m = d.shift(), n = b;
        for (a.doc.iter(b, c, function (b) {
            if (m && m.to == n && (m = d.shift()), Cd(a.doc, b)) {
                if (0 != b.height && ke(b, 0), b.widgets && k && k.previousSibling)for (var c = 0; c < b.widgets.length; ++c) {
                    var e = b.widgets[c];
                    if (e.showIfHidden) {
                        var h = k.previousSibling;
                        if (/pre/i.test(h.nodeName)) {
                            var o = gf("div", null, null, "position: relative");
                            h.parentNode.replaceChild(o, h), o.appendChild(h), h = o
                        }
                        var p = h.appendChild(gf("div", [e.node], "CodeMirror-linewidget"));
                        e.handleMouseEvents || (p.ignoreEvents = !0), X(e, p, h, g)
                    }
                }
            } else if (m && m.from <= n && m.to > n) {
                for (; k.lineObj != b;)k = l(k);
                i && n >= f && k.lineNumber && kf(k.lineNumber, N(a.options, n)), k = k.nextSibling
            } else {
                if (b.widgets)for (var s, q = 0, r = k; r && 20 > q; ++q, r = r.nextSibling)if (r.lineObj == b && /div/i.test(r.nodeName)) {
                    s = r;
                    break
                }
                var t = W(a, b, n, g, s);
                if (t != s)j.insertBefore(t, k); else {
                    for (; k != s;)k = l(k);
                    k = k.nextSibling
                }
                t.lineObj = b
            }
            ++n
        }); k;)k = l(k)
    }

    function W(a, b, d, e, f) {
        var j, g = Td(a, b), h = b.gutterMarkers, i = a.display;
        if (!(a.options.lineNumbers || h || b.bgClass || b.wrapClass || b.widgets))return g;
        if (f) {
            f.alignable = null;
            for (var o, k = !0, l = 0, m = null, n = f.firstChild; n; n = o)if (o = n.nextSibling, /\bCodeMirror-linewidget\b/.test(n.className)) {
                for (var p = 0; p < b.widgets.length; ++p) {
                    var q = b.widgets[p];
                    if (q.node == n.firstChild) {
                        q.above || m || (m = n), X(q, n, f, e), ++l;
                        break
                    }
                }
                if (p == b.widgets.length) {
                    k = !1;
                    break
                }
            } else f.removeChild(n);
            f.insertBefore(g, m), k && l == b.widgets.length && (j = f, f.className = b.wrapClass || "")
        }
        if (j || (j = gf("div", null, b.wrapClass, "position: relative"), j.appendChild(g)), b.bgClass && j.insertBefore(gf("div", null, b.bgClass + " CodeMirror-linebackground"), j.firstChild), a.options.lineNumbers || h) {
            var r = j.insertBefore(gf("div", null, null, "position: absolute; left: " + (a.options.fixedGutter ? e.fixedPos : -e.gutterTotalWidth) + "px"), j.firstChild);
            if (a.options.fixedGutter && (j.alignable || (j.alignable = [])).push(r), !a.options.lineNumbers || h && h["CodeMirror-linenumbers"] || (j.lineNumber = r.appendChild(gf("div", N(a.options, d), "CodeMirror-linenumber CodeMirror-gutter-elt", "left: " + e.gutterLeft["CodeMirror-linenumbers"] + "px; width: " + i.lineNumInnerWidth + "px"))), h)for (var s = 0; s < a.options.gutters.length; ++s) {
                var t = a.options.gutters[s], u = h.hasOwnProperty(t) && h[t];
                u && r.appendChild(gf("div", [u], "CodeMirror-gutter-elt", "left: " + e.gutterLeft[t] + "px; width: " + e.gutterWidth[t] + "px"))
            }
        }
        if (c && (j.style.zIndex = 2), b.widgets && j != f)for (var p = 0, v = b.widgets; p < v.length; ++p) {
            var q = v[p], w = gf("div", [q.node], "CodeMirror-linewidget");
            q.handleMouseEvents || (w.ignoreEvents = !0), X(q, w, j, e), q.above ? j.insertBefore(w, a.options.lineNumbers && 0 != b.height ? r : g) : j.appendChild(w), Me(q, "redraw")
        }
        return j
    }

    function X(a, b, c, d) {
        if (a.noHScroll) {
            (c.alignable || (c.alignable = [])).push(b);
            var e = d.wrapperWidth;
            b.style.left = d.fixedPos + "px", a.coverGutter || (e -= d.gutterTotalWidth, b.style.paddingLeft = d.gutterTotalWidth + "px"), b.style.width = e + "px"
        }
        a.coverGutter && (b.style.zIndex = 5, b.style.position = "relative", a.noHScroll || (b.style.marginLeft = -d.gutterTotalWidth + "px"))
    }

    function Y(a) {
        var b = a.display, c = yc(a.doc.sel.from, a.doc.sel.to);
        if (c || a.options.showCursorWhenSelecting ? Z(a) : b.cursor.style.display = b.otherCursor.style.display = "none", c ? b.selectionDiv.style.display = "none" : $(a), a.options.moveInputWithCursor) {
            var d = tb(a, a.doc.sel.head, "div"), e = lf(b.wrapper), f = lf(b.lineDiv);
            b.inputDiv.style.top = Math.max(0, Math.min(b.wrapper.clientHeight - 10, d.top + f.top - e.top)) + "px", b.inputDiv.style.left = Math.max(0, Math.min(b.wrapper.clientWidth - 10, d.left + f.left - e.left)) + "px"
        }
    }

    function Z(a) {
        var b = a.display, c = tb(a, a.doc.sel.head, "div");
        b.cursor.style.left = c.left + "px", b.cursor.style.top = c.top + "px", b.cursor.style.height = Math.max(0, c.bottom - c.top) * a.options.cursorHeight + "px", b.cursor.style.display = "", c.other ? (b.otherCursor.style.display = "", b.otherCursor.style.left = c.other.left + "px", b.otherCursor.style.top = c.other.top + "px", b.otherCursor.style.height = .85 * (c.other.bottom - c.other.top) + "px") : b.otherCursor.style.display = "none"
    }

    function $(a) {
        function h(a, b, c, d) {
            0 > b && (b = 0), e.appendChild(gf("div", null, "CodeMirror-selected", "position: absolute; left: " + a + "px; top: " + b + "px; width: " + (null == c ? f - a : c) + "px; height: " + (d - b) + "px"))
        }

        function i(b, d, e) {
            function m(c, d) {
                return sb(a, xc(b, c), "div", i, d)
            }

            var k, l, i = he(c, b), j = i.text.length;
            return wf(oe(i), d || 0, null == e ? j : e, function (a, b, c) {
                var n, o, p, i = m(a, "left");
                if (a == b)n = i, o = p = i.left; else {
                    if (n = m(b - 1, "right"), "rtl" == c) {
                        var q = i;
                        i = n, n = q
                    }
                    o = i.left, p = n.right
                }
                null == d && 0 == a && (o = g), n.top - i.top > 3 && (h(o, i.top, null, i.bottom), o = g, i.bottom < n.top && h(o, i.bottom, null, n.top)), null == e && b == j && (p = f), (!k || i.top < k.top || i.top == k.top && i.left < k.left) && (k = i), (!l || n.bottom > l.bottom || n.bottom == l.bottom && n.right > l.right) && (l = n), g + 1 > o && (o = g), h(o, n.top, p - o, n.bottom)
            }), {start: k, end: l}
        }

        var b = a.display, c = a.doc, d = a.doc.sel, e = document.createDocumentFragment(), f = b.lineSpace.offsetWidth, g = gb(a.display);
        if (d.from.line == d.to.line)i(d.from.line, d.from.ch, d.to.ch); else {
            var j = he(c, d.from.line), k = he(c, d.to.line), l = Bd(c, j) == Bd(c, k), m = i(d.from.line, d.from.ch, l ? j.text.length : null).end, n = i(d.to.line, l ? 0 : null, d.to.ch).start;
            l && (m.top < n.top - 2 ? (h(m.right, m.top, null, m.bottom), h(g, n.top, n.left, n.bottom)) : h(m.right, m.top, n.left - m.right, m.bottom)), m.bottom < n.top && h(g, m.bottom, null, n.top)
        }
        jf(b.selectionDiv, e), b.selectionDiv.style.display = ""
    }

    function _(a) {
        if (a.state.focused) {
            var b = a.display;
            clearInterval(b.blinker);
            var c = !0;
            b.cursor.style.visibility = b.otherCursor.style.visibility = "", a.options.cursorBlinkRate > 0 && (b.blinker = setInterval(function () {
                b.cursor.style.visibility = b.otherCursor.style.visibility = (c = !c) ? "" : "hidden"
            }, a.options.cursorBlinkRate))
        }
    }

    function ab(a, b) {
        a.doc.mode.startState && a.doc.frontier < a.display.showingTo && a.state.highlight.set(b, bf(bb, a))
    }

    function bb(a) {
        var b = a.doc;
        if (b.frontier < b.first && (b.frontier = b.first), !(b.frontier >= a.display.showingTo)) {
            var f, c = +new Date + a.options.workTime, d = dd(b.mode, db(a, b.frontier)), e = [];
            b.iter(b.frontier, Math.min(b.first + b.size, a.display.showingTo + 500), function (g) {
                if (b.frontier >= a.display.showingFrom) {
                    var h = g.styles;
                    g.styles = Od(a, g, d);
                    for (var i = !h || h.length != g.styles.length, j = 0; !i && j < h.length; ++j)i = h[j] != g.styles[j];
                    i && (f && f.end == b.frontier ? f.end++ : e.push(f = {
                        start: b.frontier,
                        end: b.frontier + 1
                    })), g.stateAfter = dd(b.mode, d)
                } else Qd(a, g, d), g.stateAfter = 0 == b.frontier % 5 ? dd(b.mode, d) : null;
                return ++b.frontier, +new Date > c ? (ab(a, a.options.workDelay), !0) : void 0
            }), e.length && Db(a, function () {
                for (var a = 0; a < e.length; ++a)Gb(this, e[a].start, e[a].end)
            })()
        }
    }

    function cb(a, b, c) {
        for (var d, e, f = a.doc, g = a.doc.mode.innerMode ? 1e3 : 100, h = b, i = b - g; h > i; --h) {
            if (h <= f.first)return f.first;
            var j = he(f, h - 1);
            if (j.stateAfter && (!c || h <= f.frontier))return h;
            var k = Ue(j.text, null, a.options.tabSize);
            (null == e || d > k) && (e = h - 1, d = k)
        }
        return e
    }

    function db(a, b, c) {
        var d = a.doc, e = a.display;
        if (!d.mode.startState)return !0;
        var f = cb(a, b, c), g = f > d.first && he(d, f - 1).stateAfter;
        return g = g ? dd(d.mode, g) : ed(d.mode), d.iter(f, b, function (c) {
            Qd(a, c, g);
            var h = f == b - 1 || 0 == f % 5 || f >= e.showingFrom && f < e.showingTo;
            c.stateAfter = h ? dd(d.mode, g) : null, ++f
        }), g
    }

    function eb(a) {
        return a.lineSpace.offsetTop
    }

    function fb(a) {
        return a.mover.offsetHeight - a.lineSpace.offsetHeight
    }

    function gb(a) {
        var b = jf(a.measure, gf("pre", null, null, "text-align: left")).appendChild(gf("span", "x"));
        return b.offsetLeft
    }

    function hb(a, b, c, d, e) {
        var f = -1;
        d = d || kb(a, b);
        for (var g = c; ; g += f) {
            var h = d[g];
            if (h)break;
            0 > f && 0 == g && (f = 1)
        }
        return e = g > c ? "left" : c > g ? "right" : e, "left" == e && h.leftSide ? h = h.leftSide : "right" == e && h.rightSide && (h = h.rightSide), {
            left: c > g ? h.right : h.left,
            right: g > c ? h.left : h.right,
            top: h.top,
            bottom: h.bottom
        }
    }

    function ib(a, b) {
        for (var c = a.display.measureLineCache, d = 0; d < c.length; ++d) {
            var e = c[d];
            if (e.text == b.text && e.markedSpans == b.markedSpans && a.display.scroller.clientWidth == e.width && e.classes == b.textClass + "|" + b.bgClass + "|" + b.wrapClass)return e
        }
    }

    function jb(a, b) {
        var c = ib(a, b);
        c && (c.text = c.measure = c.markedSpans = null)
    }

    function kb(a, b) {
        var c = ib(a, b);
        if (c)return c.measure;
        var d = lb(a, b), e = a.display.measureLineCache, f = {
            text: b.text,
            width: a.display.scroller.clientWidth,
            markedSpans: b.markedSpans,
            measure: d,
            classes: b.textClass + "|" + b.bgClass + "|" + b.wrapClass
        };
        return 16 == e.length ? e[++a.display.measureLineCachePos % 16] = f : e.push(f), d
    }

    function lb(a, e) {
        function t(a) {
            var b = a.top - p.top, c = a.bottom - p.top;
            c > s && (c = s), 0 > b && (b = 0);
            for (var d = q.length - 2; d >= 0; d -= 2) {
                var e = q[d], f = q[d + 1];
                if (!(e > c || b > f) && (b >= e && f >= c || e >= b && c >= f || Math.min(c, f) - Math.max(b, e) >= c - b >> 1)) {
                    q[d] = Math.min(b, e), q[d + 1] = Math.max(c, f);
                    break
                }
            }
            return 0 > d && (d = q.length, q.push(b, c)), {
                left: a.left - p.left,
                right: a.right - p.left,
                top: d,
                bottom: null
            }
        }

        function u(a) {
            a.bottom = q[a.top + 1], a.top = q[a.top]
        }

        var f = a.display, g = af(e.text.length), h = Td(a, e, g, !0);
        if (b && !c && !a.options.lineWrapping && h.childNodes.length > 100) {
            for (var i = document.createDocumentFragment(), j = 10, k = h.childNodes.length, l = 0, m = Math.ceil(k / j); m > l; ++l) {
                for (var n = gf("div", null, null, "display: inline-block"), o = 0; j > o && k; ++o)n.appendChild(h.firstChild), --k;
                i.appendChild(n)
            }
            h.appendChild(i)
        }
        jf(f.measure, h);
        var p = lf(f.lineDiv), q = [], r = af(e.text.length), s = h.offsetHeight;
        d && f.measure.first != h && jf(f.measure, h);
        for (var v, l = 0; l < g.length; ++l)if (v = g[l]) {
            var w = v, x = null;
            if (/\bCodeMirror-widget\b/.test(v.className) && v.getClientRects) {
                1 == v.firstChild.nodeType && (w = v.firstChild);
                var y = w.getClientRects();
                y.length > 1 && (x = r[l] = t(y[0]), x.rightSide = t(y[y.length - 1]))
            }
            x || (x = r[l] = t(lf(w))), v.measureRight && (x.right = lf(v.measureRight).left), v.leftSide && (x.leftSide = t(lf(v.leftSide)))
        }
        hf(a.display.measure);
        for (var v, l = 0; l < r.length; ++l)(v = r[l]) && (u(v), v.leftSide && u(v.leftSide), v.rightSide && u(v.rightSide));
        return r
    }

    function mb(a, b) {
        var c = !1;
        if (b.markedSpans)for (var d = 0; d < b.markedSpans; ++d) {
            var e = b.markedSpans[d];
            !e.collapsed || null != e.to && e.to != b.text.length || (c = !0)
        }
        var f = !c && ib(a, b);
        if (f)return hb(a, b, b.text.length, f.measure, "right").right;
        var g = Td(a, b, null, !0), h = g.appendChild(rf(a.display.measure));
        return jf(a.display.measure, g), lf(h).right - lf(a.display.lineDiv).left
    }

    function nb(a) {
        a.display.measureLineCache.length = a.display.measureLineCachePos = 0, a.display.cachedCharWidth = a.display.cachedTextHeight = null, a.options.lineWrapping || (a.display.maxLineChanged = !0), a.display.lineNumChars = null
    }

    function ob() {
        return window.pageXOffset || (document.documentElement || document.body).scrollLeft
    }

    function pb() {
        return window.pageYOffset || (document.documentElement || document.body).scrollTop
    }

    function qb(a, b, c, d) {
        if (b.widgets)for (var e = 0; e < b.widgets.length; ++e)if (b.widgets[e].above) {
            var f = Id(b.widgets[e]);
            c.top += f, c.bottom += f
        }
        if ("line" == d)return c;
        d || (d = "local");
        var g = ne(a, b);
        if ("local" == d ? g += eb(a.display) : g -= a.display.viewOffset, "page" == d || "window" == d) {
            var h = lf(a.display.lineSpace);
            g += h.top + ("window" == d ? 0 : pb());
            var i = h.left + ("window" == d ? 0 : ob());
            c.left += i, c.right += i
        }
        return c.top += g, c.bottom += g, c
    }

    function rb(a, b, c) {
        if ("div" == c)return b;
        var d = b.left, e = b.top;
        if ("page" == c)d -= ob(), e -= pb(); else if ("local" == c || !c) {
            var f = lf(a.display.sizer);
            d += f.left, e += f.top
        }
        var g = lf(a.display.lineSpace);
        return {left: d - g.left, top: e - g.top}
    }

    function sb(a, b, c, d, e) {
        return d || (d = he(a.doc, b.line)), qb(a, d, hb(a, d, b.ch, null, e), c)
    }

    function tb(a, b, c, d, e) {
        function f(b, f) {
            var g = hb(a, d, b, e, f ? "right" : "left");
            return f ? g.left = g.right : g.right = g.left, qb(a, d, g, c)
        }

        function g(a, b) {
            var c = h[b], d = c.level % 2;
            return a == xf(c) && b && c.level < h[b - 1].level ? (c = h[--b], a = yf(c) - (c.level % 2 ? 0 : 1), d = !0) : a == yf(c) && b < h.length - 1 && c.level < h[b + 1].level && (c = h[++b], a = xf(c) - c.level % 2, d = !1), d && a == c.to && a > c.from ? f(a - 1) : f(a, d)
        }

        d = d || he(a.doc, b.line), e || (e = kb(a, d));
        var h = oe(d), i = b.ch;
        if (!h)return f(i);
        var j = Ff(h, i), k = g(i, j);
        return null != Ef && (k.other = g(i, Ef)), k
    }

    function ub(a, b, c, d) {
        var e = new xc(a, b);
        return e.xRel = d, c && (e.outside = !0), e
    }

    function vb(a, b, c) {
        var d = a.doc;
        if (c += a.display.viewOffset, 0 > c)return ub(d.first, 0, !0, -1);
        var e = me(d, c), f = d.first + d.size - 1;
        if (e > f)return ub(d.first + d.size - 1, he(d, f).text.length, !0, 1);
        for (0 > b && (b = 0); ;) {
            var g = he(d, e), h = wb(a, g, e, b, c), i = Ad(g), j = i && i.find();
            if (!i || !(h.ch > j.from.ch || h.ch == j.from.ch && h.xRel > 0))return h;
            e = j.to.line
        }
    }

    function wb(a, b, c, d, e) {
        function j(d) {
            var e = tb(a, xc(c, d), "line", b, i);
            return g = !0, f > e.bottom ? e.left - h : f < e.top ? e.left + h : (g = !1, e.left)
        }

        var f = e - ne(a, b), g = !1, h = 2 * a.display.wrapper.clientWidth, i = kb(a, b), k = oe(b), l = b.text.length, m = zf(b), n = Af(b), o = j(m), p = g, q = j(n), r = g;
        if (d > q)return ub(c, n, r, 1);
        for (; ;) {
            if (k ? n == m || n == Hf(b, m, 1) : 1 >= n - m) {
                for (var s = o > d || q - d >= d - o ? m : n, t = d - (s == m ? o : q); ff.test(b.text.charAt(s));)++s;
                var u = ub(c, s, s == m ? p : r, 0 > t ? -1 : t ? 1 : 0);
                return u
            }
            var v = Math.ceil(l / 2), w = m + v;
            if (k) {
                w = m;
                for (var x = 0; v > x; ++x)w = Hf(b, w, 1)
            }
            var y = j(w);
            y > d ? (n = w, q = y, (r = g) && (q += 1e3), l = v) : (m = w, o = y, p = g, l -= v)
        }
    }

    function yb(a) {
        if (null != a.cachedTextHeight)return a.cachedTextHeight;
        if (null == xb) {
            xb = gf("pre");
            for (var b = 0; 49 > b; ++b)xb.appendChild(document.createTextNode("x")), xb.appendChild(gf("br"));
            xb.appendChild(document.createTextNode("x"))
        }
        jf(a.measure, xb);
        var c = xb.offsetHeight / 50;
        return c > 3 && (a.cachedTextHeight = c), hf(a.measure), c || 1
    }

    function zb(a) {
        if (null != a.cachedCharWidth)return a.cachedCharWidth;
        var b = gf("span", "x"), c = gf("pre", [b]);
        jf(a.measure, c);
        var d = b.offsetWidth;
        return d > 2 && (a.cachedCharWidth = d), d || 10
    }

    function Bb(a) {
        a.curOp = {
            changes: [],
            forceUpdate: !1,
            updateInput: null,
            userSelChange: null,
            textChanged: null,
            selectionChanged: !1,
            cursorActivity: !1,
            updateMaxLine: !1,
            updateScrollPos: !1,
            id: ++Ab
        }, Le++ || (Ke = [])
    }

    function Cb(a) {
        var b = a.curOp, c = a.doc, d = a.display;
        if (a.curOp = null, b.updateMaxLine && H(a), d.maxLineChanged && !a.options.lineWrapping && d.maxLine) {
            var e = mb(a, d.maxLine);
            d.sizer.style.minWidth = Math.max(0, e + 3 + Re) + "px", d.maxLineChanged = !1;
            var f = Math.max(0, d.sizer.offsetLeft + d.sizer.offsetWidth - d.scroller.clientWidth);
            f < c.scrollLeft && !b.updateScrollPos && Yb(a, Math.min(d.scroller.scrollLeft, f), !0)
        }
        var g, h;
        if (b.updateScrollPos)g = b.updateScrollPos; else if (b.selectionChanged && d.scroller.clientHeight) {
            var i = tb(a, c.sel.head);
            g = Nc(a, i.left, i.top, i.left, i.bottom)
        }
        (b.changes.length || b.forceUpdate || g && null != g.scrollTop) && (h = P(a, b.changes, g && g.scrollTop, b.forceUpdate), a.display.scroller.offsetHeight && (a.doc.scrollTop = a.display.scroller.scrollTop)), !h && b.selectionChanged && Y(a), b.updateScrollPos ? (d.scroller.scrollTop = d.scrollbarV.scrollTop = c.scrollTop = g.scrollTop, d.scroller.scrollLeft = d.scrollbarH.scrollLeft = c.scrollLeft = g.scrollLeft, L(a), b.scrollToPos && Lc(a, Cc(a.doc, b.scrollToPos), b.scrollToPosMargin)) : g && Kc(a), b.selectionChanged && _(a), a.state.focused && b.updateInput && Kb(a, b.userSelChange);
        var j = b.maybeHiddenMarkers, k = b.maybeUnhiddenMarkers;
        if (j)for (var l = 0; l < j.length; ++l)j[l].lines.length || Je(j[l], "hide");
        if (k)for (var l = 0; l < k.length; ++l)k[l].lines.length && Je(k[l], "unhide");
        var m;
        if (--Le || (m = Ke, Ke = null), b.textChanged && Je(a, "change", a, b.textChanged), b.cursorActivity && Je(a, "cursorActivity", a), m)for (var l = 0; l < m.length; ++l)m[l]()
    }

    function Db(a, b) {
        return function () {
            var c = a || this, d = !c.curOp;
            d && Bb(c);
            try {
                var e = b.apply(c, arguments)
            } finally {
                d && Cb(c)
            }
            return e
        }
    }

    function Eb(a) {
        return function () {
            var c, b = this.cm && !this.cm.curOp;
            b && Bb(this.cm);
            try {
                c = a.apply(this, arguments)
            } finally {
                b && Cb(this.cm)
            }
            return c
        }
    }

    function Fb(a, b) {
        var d, c = !a.curOp;
        c && Bb(a);
        try {
            d = b()
        } finally {
            c && Cb(a)
        }
        return d
    }

    function Gb(a, b, c, d) {
        null == b && (b = a.doc.first), null == c && (c = a.doc.first + a.doc.size), a.curOp.changes.push({
            from: b,
            to: c,
            diff: d
        })
    }

    function Hb(a) {
        a.display.pollingFast || a.display.poll.set(a.options.pollInterval, function () {
            Jb(a), a.state.focused && Hb(a)
        })
    }

    function Ib(a) {
        function c() {
            var d = Jb(a);
            d || b ? (a.display.pollingFast = !1, Hb(a)) : (b = !0, a.display.poll.set(60, c))
        }

        var b = !1;
        a.display.pollingFast = !0, a.display.poll.set(20, c)
    }

    function Jb(a) {
        var c = a.display.input, e = a.display.prevInput, f = a.doc, g = f.sel;
        if (!a.state.focused || tf(c) || Mb(a) || a.state.disableInput)return !1;
        a.state.pasteIncoming && a.state.fakedLastChar && (c.value = c.value.substring(0, c.value.length - 1), a.state.fakedLastChar = !1);
        var h = c.value;
        if (h == e && yc(g.from, g.to))return !1;
        if (b && !d && a.display.inputHasSelection === h)return Kb(a, !0), !1;
        var i = !a.curOp;
        i && Bb(a), g.shift = !1;
        for (var j = 0, k = Math.min(e.length, h.length); k > j && e.charCodeAt(j) == h.charCodeAt(j);)++j;
        var l = g.from, m = g.to;
        j < e.length ? l = xc(l.line, l.ch - (e.length - j)) : a.state.overwrite && yc(l, m) && !a.state.pasteIncoming && (m = xc(m.line, Math.min(he(f, m.line).text.length, m.ch + (h.length - j))));
        var n = a.curOp.updateInput, o = {
            from: l,
            to: m,
            text: sf(h.slice(j)),
            origin: a.state.pasteIncoming ? "paste" : "+input"
        };
        return qc(a.doc, o, "end"), a.curOp.updateInput = n, Me(a, "inputRead", a, o), h.length > 1e3 || h.indexOf("\n") > -1 ? c.value = a.display.prevInput = "" : a.display.prevInput = h, i && Cb(a), a.state.pasteIncoming = !1, !0
    }

    function Kb(a, c) {
        var e, f, g = a.doc;
        if (yc(g.sel.from, g.sel.to))c && (a.display.prevInput = a.display.input.value = "", b && !d && (a.display.inputHasSelection = null)); else {
            a.display.prevInput = "", e = uf && (g.sel.to.line - g.sel.from.line > 100 || (f = a.getSelection()).length > 1e3);
            var h = e ? "-" : f || a.getSelection();
            a.display.input.value = h, a.state.focused && Ye(a.display.input), b && !d && (a.display.inputHasSelection = h)
        }
        a.display.inaccurateSelection = e
    }

    function Lb(a) {
        "nocursor" == a.options.readOnly || o && document.activeElement == a.display.input || a.display.input.focus()
    }

    function Mb(a) {
        return a.options.readOnly || a.doc.cantEdit
    }

    function Nb(a) {
        function d() {
            a.state.focused && setTimeout(bf(Lb, a), 0)
        }

        function g() {
            null == f && (f = setTimeout(function () {
                f = null, c.cachedCharWidth = c.cachedTextHeight = of = null, nb(a), Fb(a, bf(Gb, a))
            }, 100))
        }

        function h() {
            for (var a = c.wrapper.parentNode; a && a != document.body; a = a.parentNode);
            a ? setTimeout(h, 5e3) : Ie(window, "resize", g)
        }

        function i(b) {
            Ne(a, b) || a.options.onDragEvent && a.options.onDragEvent(a, Ae(b)) || Ee(b)
        }

        function k() {
            c.inaccurateSelection && (c.prevInput = "", c.inaccurateSelection = !1, c.input.value = a.getSelection(), Ye(c.input))
        }

        var c = a.display;
        He(c.scroller, "mousedown", Db(a, Sb)), b ? He(c.scroller, "dblclick", Db(a, function (b) {
            if (!Ne(a, b)) {
                var c = Pb(a, b);
                if (c && !Tb(a, b) && !Ob(a.display, b)) {
                    Be(b);
                    var d = Uc(he(a.doc, c.line).text, c);
                    Fc(a.doc, d.from, d.to)
                }
            }
        })) : He(c.scroller, "dblclick", function (b) {
            Ne(a, b) || Be(b)
        }), He(c.lineSpace, "selectstart", function (a) {
            Ob(c, a) || Be(a)
        }), t || He(c.scroller, "contextmenu", function (b) {
            lc(a, b)
        }), He(c.scroller, "scroll", function () {
            c.scroller.clientHeight && (Xb(a, c.scroller.scrollTop), Yb(a, c.scroller.scrollLeft, !0), Je(a, "scroll", a))
        }), He(c.scrollbarV, "scroll", function () {
            c.scroller.clientHeight && Xb(a, c.scrollbarV.scrollTop)
        }), He(c.scrollbarH, "scroll", function () {
            c.scroller.clientHeight && Yb(a, c.scrollbarH.scrollLeft)
        }), He(c.scroller, "mousewheel", function (b) {
            _b(a, b)
        }), He(c.scroller, "DOMMouseScroll", function (b) {
            _b(a, b)
        }), He(c.scrollbarH, "mousedown", d), He(c.scrollbarV, "mousedown", d), He(c.wrapper, "scroll", function () {
            c.wrapper.scrollTop = c.wrapper.scrollLeft = 0
        });
        var f;
        He(window, "resize", g), setTimeout(h, 5e3), He(c.input, "keyup", Db(a, function (b) {
            Ne(a, b) || a.options.onKeyEvent && a.options.onKeyEvent(a, Ae(b)) || 16 == b.keyCode && (a.doc.sel.shift = !1)
        })), He(c.input, "input", bf(Ib, a)), He(c.input, "keydown", Db(a, gc)), He(c.input, "keypress", Db(a, hc)), He(c.input, "focus", bf(ic, a)), He(c.input, "blur", bf(jc, a)), a.options.dragDrop && (He(c.scroller, "dragstart", function (b) {
            Wb(a, b)
        }), He(c.scroller, "dragenter", i), He(c.scroller, "dragover", i), He(c.scroller, "drop", Db(a, Vb))), He(c.scroller, "paste", function (b) {
            Ob(c, b) || (Lb(a), Ib(a))
        }), He(c.input, "paste", function () {
            if (e && !a.state.fakedLastChar && !(new Date - a.state.lastMiddleDown < 200)) {
                var b = c.input.selectionStart, d = c.input.selectionEnd;
                c.input.value += "$", c.input.selectionStart = b, c.input.selectionEnd = d, a.state.fakedLastChar = !0
            }
            a.state.pasteIncoming = !0, Ib(a)
        }), He(c.input, "cut", k), He(c.input, "copy", k), j && He(c.sizer, "mouseup", function () {
            document.activeElement == c.input && c.input.blur(), Lb(a)
        })
    }

    function Ob(a, b) {
        for (var c = Fe(b); c != a.wrapper; c = c.parentNode)if (!c || c.ignoreEvents || c.parentNode == a.sizer && c != a.mover)return !0
    }

    function Pb(a, b, c) {
        var d = a.display;
        if (!c) {
            var e = Fe(b);
            if (e == d.scrollbarH || e == d.scrollbarH.firstChild || e == d.scrollbarV || e == d.scrollbarV.firstChild || e == d.scrollbarFiller || e == d.gutterFiller)return null
        }
        var f, g, h = lf(d.lineSpace);
        try {
            f = b.clientX, g = b.clientY
        } catch (b) {
            return null
        }
        return vb(a, f - h.left, g - h.top)
    }

    function Sb(a) {
        function q(a) {
            if (!yc(p, a)) {
                if (p = a, "single" == j)return Fc(c.doc, Cc(f, h), a), void 0;
                if (n = Cc(f, n), o = Cc(f, o), "double" == j) {
                    var b = Uc(he(f, a.line).text, a);
                    zc(a, n) ? Fc(c.doc, b.from, o) : Fc(c.doc, n, b.to)
                } else"triple" == j && (zc(a, n) ? Fc(c.doc, o, Cc(f, xc(a.line, 0))) : Fc(c.doc, n, Cc(f, xc(a.line + 1, 0))))
            }
        }

        function u(a) {
            var b = ++s, e = Pb(c, a, !0);
            if (e)if (yc(e, l)) {
                var h = a.clientY < r.top ? -20 : a.clientY > r.bottom ? 20 : 0;
                h && setTimeout(Db(c, function () {
                    s == b && (d.scroller.scrollTop += h, u(a))
                }), 50)
            } else {
                c.state.focused || ic(c), l = e, q(e);
                var g = K(d, f);
                (e.line >= g.to || e.line < g.from) && setTimeout(Db(c, function () {
                    s == b && u(a)
                }), 150)
            }
        }

        function v(a) {
            s = 1 / 0, Be(a), Lb(c), Ie(document, "mousemove", w), Ie(document, "mouseup", x)
        }

        if (!Ne(this, a)) {
            var c = this, d = c.display, f = c.doc, g = f.sel;
            if (g.shift = a.shiftKey, Ob(d, a))return e || (d.scroller.draggable = !1, setTimeout(function () {
                d.scroller.draggable = !0
            }, 100)), void 0;
            if (!Tb(c, a)) {
                var h = Pb(c, a);
                switch (Ge(a)) {
                    case 3:
                        return t && lc.call(c, c, a), void 0;
                    case 2:
                        return e && (c.state.lastMiddleDown = +new Date), h && Fc(c.doc, h), setTimeout(bf(Lb, c), 20), Be(a), void 0
                }
                if (!h)return Fe(a) == d.scroller && Be(a), void 0;
                c.state.focused || ic(c);
                var i = +new Date, j = "single";
                if (Rb && Rb.time > i - 400 && yc(Rb.pos, h))j = "triple", Be(a), setTimeout(bf(Lb, c), 20), Vc(c, h.line); else if (Qb && Qb.time > i - 400 && yc(Qb.pos, h)) {
                    j = "double", Rb = {time: i, pos: h}, Be(a);
                    var k = Uc(he(f, h.line).text, h);
                    Fc(c.doc, k.from, k.to)
                } else Qb = {time: i, pos: h};
                var l = h;
                if (c.options.dragDrop && mf && !Mb(c) && !yc(g.from, g.to) && !zc(h, g.from) && !zc(g.to, h) && "single" == j) {
                    var m = Db(c, function (b) {
                        e && (d.scroller.draggable = !1), c.state.draggingText = !1, Ie(document, "mouseup", m), Ie(d.scroller, "drop", m), Math.abs(a.clientX - b.clientX) + Math.abs(a.clientY - b.clientY) < 10 && (Be(b), Fc(c.doc, h), Lb(c))
                    });
                    return e && (d.scroller.draggable = !0), c.state.draggingText = m, d.scroller.dragDrop && d.scroller.dragDrop(), He(document, "mouseup", m), He(d.scroller, "drop", m), void 0
                }
                Be(a), "single" == j && Fc(c.doc, Cc(f, h));
                var n = g.from, o = g.to, p = h, r = lf(d.wrapper), s = 0, w = Db(c, function (a) {
                    b || Ge(a) ? u(a) : v(a)
                }), x = Db(c, v);
                He(document, "mousemove", w), He(document, "mouseup", x)
            }
        }
    }

    function Tb(a, b) {
        var c = a.display;
        try {
            var d = b.clientX, e = b.clientY
        } catch (b) {
            return !1
        }
        if (d >= Math.floor(lf(c.gutters).right))return !1;
        if (Be(b), !Pe(a, "gutterClick"))return !0;
        var f = lf(c.lineDiv);
        if (e > f.bottom)return !0;
        e -= f.top - c.viewOffset;
        for (var g = 0; g < a.options.gutters.length; ++g) {
            var h = c.gutters.childNodes[g];
            if (h && lf(h).right >= d) {
                var i = me(a.doc, e), j = a.options.gutters[g];
                Me(a, "gutterClick", a, i, j, b);
                break
            }
        }
        return !0
    }

    function Vb(a) {
        var c = this;
        if (!(Ne(c, a) || Ob(c.display, a) || c.options.onDragEvent && c.options.onDragEvent(c, Ae(a)))) {
            Be(a), b && (Ub = +new Date);
            var d = Pb(c, a, !0), e = a.dataTransfer.files;
            if (d && !Mb(c))if (e && e.length && window.FileReader && window.File)for (var f = e.length, g = Array(f), h = 0, i = function (a, b) {
                var e = new FileReader;
                e.onload = function () {
                    g[b] = e.result, ++h == f && (d = Cc(c.doc, d), qc(c.doc, {
                        from: d,
                        to: d,
                        text: sf(g.join("\n")),
                        origin: "paste"
                    }, "around"))
                }, e.readAsText(a)
            }, j = 0; f > j; ++j)i(e[j], j); else {
                if (c.state.draggingText && !zc(d, c.doc.sel.from) && !zc(c.doc.sel.to, d))return c.state.draggingText(a), setTimeout(bf(Lb, c), 20), void 0;
                try {
                    var g = a.dataTransfer.getData("Text");
                    if (g) {
                        var k = c.doc.sel.from, l = c.doc.sel.to;
                        Hc(c.doc, d, d), c.state.draggingText && wc(c.doc, "", k, l, "paste"), c.replaceSelection(g, null, "paste"), Lb(c), ic(c)
                    }
                } catch (a) {
                }
            }
        }
    }

    function Wb(a, c) {
        if (b && (!a.state.draggingText || +new Date - Ub < 100))return Ee(c), void 0;
        if (!Ne(a, c) && !Ob(a.display, c)) {
            var d = a.getSelection();
            if (c.dataTransfer.setData("Text", d), c.dataTransfer.setDragImage && !i) {
                var e = gf("img", null, null, "position: fixed; left: 0; top: 0;");
                h && (e.width = e.height = 1, a.display.wrapper.appendChild(e), e._top = e.offsetTop), c.dataTransfer.setDragImage(e, 0, 0), h && e.parentNode.removeChild(e)
            }
        }
    }

    function Xb(b, c) {
        Math.abs(b.doc.scrollTop - c) < 2 || (b.doc.scrollTop = c, a || P(b, [], c), b.display.scroller.scrollTop != c && (b.display.scroller.scrollTop = c), b.display.scrollbarV.scrollTop != c && (b.display.scrollbarV.scrollTop = c), a && P(b, []), ab(b, 100))
    }

    function Yb(a, b, c) {
        (c ? b == a.doc.scrollLeft : Math.abs(a.doc.scrollLeft - b) < 2) || (b = Math.min(b, a.display.scroller.scrollWidth - a.display.scroller.clientWidth), a.doc.scrollLeft = b, L(a), a.display.scroller.scrollLeft != b && (a.display.scroller.scrollLeft = b), a.display.scrollbarH.scrollLeft != b && (a.display.scrollbarH.scrollLeft = b))
    }

    function _b(b, c) {
        var d = c.wheelDeltaX, f = c.wheelDeltaY;
        null == d && c.detail && c.axis == c.HORIZONTAL_AXIS && (d = c.detail), null == f && c.detail && c.axis == c.VERTICAL_AXIS ? f = c.detail : null == f && (f = c.wheelDelta);
        var g = b.display, i = g.scroller;
        if (d && i.scrollWidth > i.clientWidth || f && i.scrollHeight > i.clientHeight) {
            if (f && p && e)for (var j = c.target; j != i; j = j.parentNode)if (j.lineObj) {
                b.display.currentWheelTarget = j;
                break
            }
            if (d && !a && !h && null != $b)return f && Xb(b, Math.max(0, Math.min(i.scrollTop + f * $b, i.scrollHeight - i.clientHeight))), Yb(b, Math.max(0, Math.min(i.scrollLeft + d * $b, i.scrollWidth - i.clientWidth))), Be(c), g.wheelStartX = null, void 0;
            if (f && null != $b) {
                var k = f * $b, l = b.doc.scrollTop, m = l + g.wrapper.clientHeight;
                0 > k ? l = Math.max(0, l + k - 50) : m = Math.min(b.doc.height, m + k + 50), P(b, [], {
                    top: l,
                    bottom: m
                })
            }
            20 > Zb && (null == g.wheelStartX ? (g.wheelStartX = i.scrollLeft, g.wheelStartY = i.scrollTop, g.wheelDX = d, g.wheelDY = f, setTimeout(function () {
                if (null != g.wheelStartX) {
                    var a = i.scrollLeft - g.wheelStartX, b = i.scrollTop - g.wheelStartY, c = b && g.wheelDY && b / g.wheelDY || a && g.wheelDX && a / g.wheelDX;
                    g.wheelStartX = g.wheelStartY = null, c && ($b = ($b * Zb + c) / (Zb + 1), ++Zb)
                }
            }, 200)) : (g.wheelDX += d, g.wheelDY += f))
        }
    }

    function ac(a, b, c) {
        if ("string" == typeof b && (b = fd[b], !b))return !1;
        a.display.pollingFast && Jb(a) && (a.display.pollingFast = !1);
        var d = a.doc, e = d.sel.shift, f = !1;
        try {
            Mb(a) && (a.state.suppressEdits = !0), c && (d.sel.shift = !1), f = b(a) != Se
        } finally {
            d.sel.shift = e, a.state.suppressEdits = !1
        }
        return f
    }

    function bc(a) {
        var b = a.state.keyMaps.slice(0);
        return a.options.extraKeys && b.push(a.options.extraKeys), b.push(a.options.keyMap), b
    }

    function dc(a, b) {
        var c = hd(a.options.keyMap), e = c.auto;
        clearTimeout(cc), e && !jd(b) && (cc = setTimeout(function () {
            hd(a.options.keyMap) == c && (a.options.keyMap = e.call ? e.call(null, a) : e, C(a))
        }, 50));
        var f = kd(b, !0), g = !1;
        if (!f)return !1;
        var h = bc(a);
        return g = b.shiftKey ? id("Shift-" + f, h, function (b) {
            return ac(a, b, !0)
        }) || id(f, h, function (b) {
            return ("string" == typeof b ? /^go[A-Z]/.test(b) : b.motion) ? ac(a, b) : void 0
        }) : id(f, h, function (b) {
            return ac(a, b)
        }), g && (Be(b), _(a), d && (b.oldKeyCode = b.keyCode, b.keyCode = 0), Me(a, "keyHandled", a, f, b)), g
    }

    function ec(a, b, c) {
        var d = id("'" + c + "'", bc(a), function (b) {
            return ac(a, b, !0)
        });
        return d && (Be(b), _(a), Me(a, "keyHandled", a, "'" + c + "'", b)), d
    }

    function gc(a) {
        var c = this;
        if (c.state.focused || ic(c), !(Ne(c, a) || c.options.onKeyEvent && c.options.onKeyEvent(c, Ae(a)))) {
            b && 27 == a.keyCode && (a.returnValue = !1);
            var d = a.keyCode;
            c.doc.sel.shift = 16 == d || a.shiftKey;
            var e = dc(c, a);
            h && (fc = e ? d : null, !e && 88 == d && !uf && (p ? a.metaKey : a.ctrlKey) && c.replaceSelection(""))
        }
    }

    function hc(a) {
        var c = this;
        if (!(Ne(c, a) || c.options.onKeyEvent && c.options.onKeyEvent(c, Ae(a)))) {
            var e = a.keyCode, f = a.charCode;
            if (h && e == fc)return fc = null, Be(a), void 0;
            if (!(h && (!a.which || a.which < 10) || j) || !dc(c, a)) {
                var g = String.fromCharCode(null == f ? e : f);
                this.options.electricChars && this.doc.mode.electricChars && this.options.smartIndent && !Mb(this) && this.doc.mode.electricChars.indexOf(g) > -1 && setTimeout(Db(c, function () {
                    Qc(c, c.doc.sel.to.line, "smart")
                }), 75), ec(c, a, g) || (b && !d && (c.display.inputHasSelection = null), Ib(c))
            }
        }
    }

    function ic(a) {
        "nocursor" != a.options.readOnly && (a.state.focused || (Je(a, "focus", a), a.state.focused = !0, -1 == a.display.wrapper.className.search(/\bCodeMirror-focused\b/) && (a.display.wrapper.className += " CodeMirror-focused"), a.curOp || (Kb(a, !0), e && setTimeout(bf(Kb, a, !0), 0))), Hb(a), _(a))
    }

    function jc(a) {
        a.state.focused && (Je(a, "blur", a), a.state.focused = !1, a.display.wrapper.className = a.display.wrapper.className.replace(" CodeMirror-focused", "")), clearInterval(a.display.blinker), setTimeout(function () {
            a.state.focused || (a.doc.sel.shift = !1)
        }, 150)
    }

    function lc(a, c) {
        function k() {
            if (null != e.input.selectionStart) {
                var a = e.input.value = " " + (yc(f.from, f.to) ? "" : e.input.value);
                e.prevInput = " ", e.input.selectionStart = 1, e.input.selectionEnd = a.length
            }
        }

        function l() {
            if (e.inputDiv.style.position = "relative", e.input.style.cssText = j, d && (e.scrollbarV.scrollTop = e.scroller.scrollTop = i), Hb(a), null != e.input.selectionStart) {
                (!b || d) && k(), clearTimeout(kc);
                var c = 0, f = function () {
                    " " == e.prevInput && 0 == e.input.selectionStart ? Db(a, fd.selectAll)(a) : c++ < 10 ? kc = setTimeout(f, 500) : Kb(a)
                };
                kc = setTimeout(f, 200)
            }
        }

        if (!Ne(a, c, "contextmenu")) {
            var e = a.display, f = a.doc.sel;
            if (!Ob(e, c)) {
                var g = Pb(a, c), i = e.scroller.scrollTop;
                if (g && !h) {
                    (yc(f.from, f.to) || zc(g, f.from) || !zc(g, f.to)) && Db(a, Hc)(a.doc, g, g);
                    var j = e.input.style.cssText;
                    if (e.inputDiv.style.position = "absolute", e.input.style.cssText = "position: fixed; width: 30px; height: 30px; top: " + (c.clientY - 5) + "px; left: " + (c.clientX - 5) + "px; z-index: 1000; background: white; outline: none;" + "border-width: 0; outline: none; overflow: hidden; opacity: .05; -ms-opacity: .05; filter: alpha(opacity=5);", Lb(a), Kb(a, !0), yc(f.from, f.to) && (e.input.value = e.prevInput = " "), b && !d && k(), t) {
                        Ee(c);
                        var m = function () {
                            Ie(window, "mouseup", m), setTimeout(l, 20)
                        };
                        He(window, "mouseup", m)
                    } else setTimeout(l, 50)
                }
            }
        }
    }

    function nc(a, b, c) {
        if (!zc(b.from, c))return Cc(a, c);
        var d = b.text.length - 1 - (b.to.line - b.from.line);
        if (c.line > b.to.line + d) {
            var e = c.line - d, f = a.first + a.size - 1;
            return e > f ? xc(f, he(a, f).text.length) : Dc(c, he(a, e).text.length)
        }
        if (c.line == b.to.line + d)return Dc(c, Xe(b.text).length + (1 == b.text.length ? b.from.ch : 0) + he(a, b.to.line).text.length - b.to.ch);
        var g = c.line - b.from.line;
        return Dc(c, b.text[g].length + (g ? 0 : b.from.ch))
    }

    function oc(a, b, c) {
        if (c && "object" == typeof c)return {anchor: nc(a, b, c.anchor), head: nc(a, b, c.head)};
        if ("start" == c)return {anchor: b.from, head: b.from};
        var d = mc(b);
        if ("around" == c)return {anchor: b.from, head: d};
        if ("end" == c)return {anchor: d, head: d};
        var e = function (a) {
            if (zc(a, b.from))return a;
            if (!zc(b.to, a))return d;
            var c = a.line + b.text.length - (b.to.line - b.from.line) - 1, e = a.ch;
            return a.line == b.to.line && (e += d.ch - b.to.ch), xc(c, e)
        };
        return {anchor: e(a.sel.anchor), head: e(a.sel.head)}
    }

    function pc(a, b, c) {
        var d = {
            canceled: !1, from: b.from, to: b.to, text: b.text, origin: b.origin, cancel: function () {
                this.canceled = !0
            }
        };
        return c && (d.update = function (b, c, d, e) {
            b && (this.from = Cc(a, b)), c && (this.to = Cc(a, c)), d && (this.text = d), void 0 !== e && (this.origin = e)
        }), Je(a, "beforeChange", a, d), a.cm && Je(a.cm, "beforeChange", a.cm, d), d.canceled ? null : {
            from: d.from,
            to: d.to,
            text: d.text,
            origin: d.origin
        }
    }

    function qc(a, b, c, d) {
        if (a.cm) {
            if (!a.cm.curOp)return Db(a.cm, qc)(a, b, c, d);
            if (a.cm.state.suppressEdits)return
        }
        if (!(Pe(a, "beforeChange") || a.cm && Pe(a.cm, "beforeChange")) || (b = pc(a, b, !0))) {
            var e = u && !d && xd(a, b.from, b.to);
            if (e) {
                for (var f = e.length - 1; f >= 1; --f)rc(a, {from: e[f].from, to: e[f].to, text: [""]});
                e.length && rc(a, {from: e[0].from, to: e[0].to, text: b.text}, c)
            } else rc(a, b, c)
        }
    }

    function rc(a, b, c) {
        var d = oc(a, b, c);
        se(a, b, d, a.cm ? a.cm.curOp.id : 0 / 0), uc(a, b, d, vd(a, b));
        var e = [];
        fe(a, function (a, c) {
            c || -1 != Ze(e, a.history) || (ye(a.history, b), e.push(a.history)), uc(a, b, null, vd(a, b))
        })
    }

    function sc(a, b) {
        if (!a.cm || !a.cm.state.suppressEdits) {
            var c = a.history, d = ("undo" == b ? c.done : c.undone).pop();
            if (d) {
                var e = {
                    changes: [],
                    anchorBefore: d.anchorAfter,
                    headBefore: d.headAfter,
                    anchorAfter: d.anchorBefore,
                    headAfter: d.headBefore,
                    generation: c.generation
                };
                ("undo" == b ? c.undone : c.done).push(e), c.generation = d.generation || ++c.maxGeneration;
                for (var f = Pe(a, "beforeChange") || a.cm && Pe(a.cm, "beforeChange"), g = d.changes.length - 1; g >= 0; --g) {
                    var h = d.changes[g];
                    if (h.origin = b, f && !pc(a, h, !1))return ("undo" == b ? c.done : c.undone).length = 0, void 0;
                    e.changes.push(re(a, h));
                    var i = g ? oc(a, h, null) : {anchor: d.anchorBefore, head: d.headBefore};
                    uc(a, h, i, wd(a, h));
                    var j = [];
                    fe(a, function (a, b) {
                        b || -1 != Ze(j, a.history) || (ye(a.history, h), j.push(a.history)), uc(a, h, null, wd(a, h))
                    })
                }
            }
        }
    }

    function tc(a, b) {
        function c(a) {
            return xc(a.line + b, a.ch)
        }

        a.first += b, a.cm && Gb(a.cm, a.first, a.first, b), a.sel.head = c(a.sel.head), a.sel.anchor = c(a.sel.anchor), a.sel.from = c(a.sel.from), a.sel.to = c(a.sel.to)
    }

    function uc(a, b, c, d) {
        if (a.cm && !a.cm.curOp)return Db(a.cm, uc)(a, b, c, d);
        if (b.to.line < a.first)return tc(a, b.text.length - 1 - (b.to.line - b.from.line)), void 0;
        if (!(b.from.line > a.lastLine())) {
            if (b.from.line < a.first) {
                var e = b.text.length - 1 - (a.first - b.from.line);
                tc(a, e), b = {
                    from: xc(a.first, 0),
                    to: xc(b.to.line + e, b.to.ch),
                    text: [Xe(b.text)],
                    origin: b.origin
                }
            }
            var f = a.lastLine();
            b.to.line > f && (b = {
                from: b.from,
                to: xc(f, he(a, f).text.length),
                text: [b.text[0]],
                origin: b.origin
            }), b.removed = ie(a, b.from, b.to), c || (c = oc(a, b, null)), a.cm ? vc(a.cm, b, d, c) : $d(a, b, d, c)
        }
    }

    function vc(a, b, c, d) {
        var e = a.doc, f = a.display, g = b.from, h = b.to, i = !1, j = g.line;
        a.options.lineWrapping || (j = le(Bd(e, he(e, g.line))), e.iter(j, h.line + 1, function (a) {
            return a == f.maxLine ? (i = !0, !0) : void 0
        })), zc(e.sel.head, b.from) || zc(b.to, e.sel.head) || (a.curOp.cursorActivity = !0), $d(e, b, c, d, A(a)), a.options.lineWrapping || (e.iter(j, g.line + b.text.length, function (a) {
            var b = G(e, a);
            b > f.maxLineLength && (f.maxLine = a, f.maxLineLength = b, f.maxLineChanged = !0, i = !1)
        }), i && (a.curOp.updateMaxLine = !0)), e.frontier = Math.min(e.frontier, g.line), ab(a, 400);
        var k = b.text.length - (h.line - g.line) - 1;
        if (Gb(a, g.line, h.line + 1, k), Pe(a, "change")) {
            var l = {from: g, to: h, text: b.text, removed: b.removed, origin: b.origin};
            if (a.curOp.textChanged) {
                for (var m = a.curOp.textChanged; m.next; m = m.next);
                m.next = l
            } else a.curOp.textChanged = l
        }
    }

    function wc(a, b, c, d, e) {
        if (d || (d = c), zc(d, c)) {
            var f = d;
            d = c, c = f
        }
        "string" == typeof b && (b = sf(b)), qc(a, {from: c, to: d, text: b, origin: e}, null)
    }

    function xc(a, b) {
        return this instanceof xc ? (this.line = a, this.ch = b, void 0) : new xc(a, b)
    }

    function yc(a, b) {
        return a.line == b.line && a.ch == b.ch
    }

    function zc(a, b) {
        return a.line < b.line || a.line == b.line && a.ch < b.ch
    }

    function Ac(a) {
        return xc(a.line, a.ch)
    }

    function Bc(a, b) {
        return Math.max(a.first, Math.min(b, a.first + a.size - 1))
    }

    function Cc(a, b) {
        if (b.line < a.first)return xc(a.first, 0);
        var c = a.first + a.size - 1;
        return b.line > c ? xc(c, he(a, c).text.length) : Dc(b, he(a, b.line).text.length)
    }

    function Dc(a, b) {
        var c = a.ch;
        return null == c || c > b ? xc(a.line, b) : 0 > c ? xc(a.line, 0) : a
    }

    function Ec(a, b) {
        return b >= a.first && b < a.first + a.size
    }

    function Fc(a, b, c, d) {
        if (a.sel.shift || a.sel.extend) {
            var e = a.sel.anchor;
            if (c) {
                var f = zc(b, e);
                f != zc(c, e) ? (e = b, b = c) : f != zc(b, c) && (b = c)
            }
            Hc(a, e, b, d)
        } else Hc(a, b, c || b, d);
        a.cm && (a.cm.curOp.userSelChange = !0)
    }

    function Gc(a, b, c) {
        var d = {anchor: b, head: c};
        return Je(a, "beforeSelectionChange", a, d), a.cm && Je(a.cm, "beforeSelectionChange", a.cm, d), d.anchor = Cc(a, d.anchor), d.head = Cc(a, d.head), d
    }

    function Hc(a, b, c, d, e) {
        if (!e && Pe(a, "beforeSelectionChange") || a.cm && Pe(a.cm, "beforeSelectionChange")) {
            var f = Gc(a, b, c);
            c = f.head, b = f.anchor
        }
        var g = a.sel;
        if (g.goalColumn = null, (e || !yc(b, g.anchor)) && (b = Jc(a, b, d, "push" != e)), (e || !yc(c, g.head)) && (c = Jc(a, c, d, "push" != e)), !yc(g.anchor, b) || !yc(g.head, c)) {
            g.anchor = b, g.head = c;
            var h = zc(c, b);
            g.from = h ? c : b, g.to = h ? b : c, a.cm && (a.cm.curOp.updateInput = a.cm.curOp.selectionChanged = a.cm.curOp.cursorActivity = !0), Me(a, "cursorActivity", a)
        }
    }

    function Ic(a) {
        Hc(a.doc, a.doc.sel.from, a.doc.sel.to, null, "push")
    }

    function Jc(a, b, c, d) {
        var e = !1, f = b, g = c || 1;
        a.cantEdit = !1;
        a:for (; ;) {
            var h = he(a, f.line);
            if (h.markedSpans)for (var i = 0; i < h.markedSpans.length; ++i) {
                var j = h.markedSpans[i], k = j.marker;
                if ((null == j.from || (k.inclusiveLeft ? j.from <= f.ch : j.from < f.ch)) && (null == j.to || (k.inclusiveRight ? j.to >= f.ch : j.to > f.ch))) {
                    if (d && (Je(k, "beforeCursorEnter"), k.explicitlyCleared)) {
                        if (h.markedSpans) {
                            --i;
                            continue
                        }
                        break
                    }
                    if (!k.atomic)continue;
                    var l = k.find()[0 > g ? "from" : "to"];
                    if (yc(l, f) && (l.ch += g, l.ch < 0 ? l = l.line > a.first ? Cc(a, xc(l.line - 1)) : null : l.ch > h.text.length && (l = l.line < a.first + a.size - 1 ? xc(l.line + 1, 0) : null), !l)) {
                        if (e)return d ? (a.cantEdit = !0, xc(a.first, 0)) : Jc(a, b, c, !0);
                        e = !0, l = b, g = -g
                    }
                    f = l;
                    continue a
                }
            }
            return f
        }
    }

    function Kc(a) {
        var b = Lc(a, a.doc.sel.head, a.options.cursorScrollMargin);
        if (a.state.focused) {
            var c = a.display, d = lf(c.sizer), e = null;
            if (b.top + d.top < 0 ? e = !0 : b.bottom + d.top > (window.innerHeight || document.documentElement.clientHeight) && (e = !1), null != e && !m) {
                var f = "none" == c.cursor.style.display;
                f && (c.cursor.style.display = "", c.cursor.style.left = b.left + "px", c.cursor.style.top = b.top - c.viewOffset + "px"), c.cursor.scrollIntoView(e), f && (c.cursor.style.display = "none")
            }
        }
    }

    function Lc(a, b, c) {
        for (null == c && (c = 0); ;) {
            var d = !1, e = tb(a, b), f = Nc(a, e.left, e.top - c, e.left, e.bottom + c), g = a.doc.scrollTop, h = a.doc.scrollLeft;
            if (null != f.scrollTop && (Xb(a, f.scrollTop), Math.abs(a.doc.scrollTop - g) > 1 && (d = !0)), null != f.scrollLeft && (Yb(a, f.scrollLeft), Math.abs(a.doc.scrollLeft - h) > 1 && (d = !0)), !d)return e
        }
    }

    function Mc(a, b, c, d, e) {
        var f = Nc(a, b, c, d, e);
        null != f.scrollTop && Xb(a, f.scrollTop), null != f.scrollLeft && Yb(a, f.scrollLeft)
    }

    function Nc(a, b, c, d, e) {
        var f = a.display, g = yb(a.display);
        0 > c && (c = 0);
        var h = f.scroller.clientHeight - Re, i = f.scroller.scrollTop, j = {}, k = a.doc.height + fb(f), l = g > c, m = e > k - g;
        if (i > c)j.scrollTop = l ? 0 : c; else if (e > i + h) {
            var n = Math.min(c, (m ? k : e) - h);
            n != i && (j.scrollTop = n)
        }
        var o = f.scroller.clientWidth - Re, p = f.scroller.scrollLeft;
        b += f.gutters.offsetWidth, d += f.gutters.offsetWidth;
        var q = f.gutters.offsetWidth, r = q + 10 > b;
        return p + q > b || r ? (r && (b = 0), j.scrollLeft = Math.max(0, b - 10 - q)) : d > o + p - 3 && (j.scrollLeft = d + 10 - o), j
    }

    function Oc(a, b, c) {
        a.curOp.updateScrollPos = {
            scrollLeft: null == b ? a.doc.scrollLeft : b,
            scrollTop: null == c ? a.doc.scrollTop : c
        }
    }

    function Pc(a, b, c) {
        var d = a.curOp.updateScrollPos || (a.curOp.updateScrollPos = {
                scrollLeft: a.doc.scrollLeft,
                scrollTop: a.doc.scrollTop
            }), e = a.display.scroller;
        d.scrollTop = Math.max(0, Math.min(e.scrollHeight - e.clientHeight, d.scrollTop + c)), d.scrollLeft = Math.max(0, Math.min(e.scrollWidth - e.clientWidth, d.scrollLeft + b))
    }

    function Qc(a, b, c, d) {
        var e = a.doc;
        if (null == c && (c = "add"), "smart" == c)if (a.doc.mode.indent)var f = db(a, b); else c = "prev";
        var k, g = a.options.tabSize, h = he(e, b), i = Ue(h.text, null, g), j = h.text.match(/^\s*/)[0];
        if ("smart" == c && (k = a.doc.mode.indent(f, h.text.slice(j.length), h.text), k == Se)) {
            if (!d)return;
            c = "prev"
        }
        "prev" == c ? k = b > e.first ? Ue(he(e, b - 1).text, null, g) : 0 : "add" == c ? k = i + a.options.indentUnit : "subtract" == c ? k = i - a.options.indentUnit : "number" == typeof c && (k = i + c), k = Math.max(0, k);
        var l = "", m = 0;
        if (a.options.indentWithTabs)for (var n = Math.floor(k / g); n; --n)m += g, l += "	";
        k > m && (l += We(k - m)), l != j && wc(a.doc, l, xc(b, 0), xc(b, j.length), "+input"), h.stateAfter = null
    }

    function Rc(a, b, c) {
        var d = b, e = b, f = a.doc;
        return "number" == typeof b ? e = he(f, Bc(f, b)) : d = le(b), null == d ? null : c(e, d) ? (Gb(a, d, d + 1), e) : null
    }

    function Sc(a, b, c, d, e) {
        function k() {
            var b = f + c;
            return b < a.first || b >= a.first + a.size ? j = !1 : (f = b, i = he(a, b))
        }

        function l(a) {
            var b = (e ? Hf : If)(i, g, c, !0);
            if (null == b) {
                if (a || !k())return j = !1;
                g = e ? (0 > c ? Af : zf)(i) : 0 > c ? i.text.length : 0
            } else g = b;
            return !0
        }

        var f = b.line, g = b.ch, h = c, i = he(a, f), j = !0;
        if ("char" == d)l(); else if ("column" == d)l(!0); else if ("word" == d || "group" == d)for (var m = null, n = "group" == d, o = !0; !(0 > c) || l(!o); o = !1) {
            var p = i.text.charAt(g) || "\n", q = df(p) ? "w" : n ? /\s/.test(p) ? null : "p" : null;
            if (m && m != q) {
                0 > c && (c = 1, l());
                break
            }
            if (q && (m = q), c > 0 && !l(!o))break
        }
        var r = Jc(a, xc(f, g), h, !0);
        return j || (r.hitSide = !0), r
    }

    function Tc(a, b, c, d) {
        var g, e = a.doc, f = b.left;
        if ("page" == d) {
            var h = Math.min(a.display.wrapper.clientHeight, window.innerHeight || document.documentElement.clientHeight);
            g = b.top + c * (h - (0 > c ? 1.5 : .5) * yb(a.display))
        } else"line" == d && (g = c > 0 ? b.bottom + 3 : b.top - 3);
        for (; ;) {
            var i = vb(a, f, g);
            if (!i.outside)break;
            if (0 > c ? 0 >= g : g >= e.height) {
                i.hitSide = !0;
                break
            }
            g += 5 * c
        }
        return i
    }

    function Uc(a, b) {
        var c = b.ch, d = b.ch;
        if (a) {
            (b.xRel < 0 || d == a.length) && c ? --c : ++d;
            for (var e = a.charAt(c), f = df(e) ? df : /\s/.test(e) ? function (a) {
                return /\s/.test(a)
            } : function (a) {
                return !/\s/.test(a) && !df(a)
            }; c > 0 && f(a.charAt(c - 1));)--c;
            for (; d < a.length && f(a.charAt(d));)++d
        }
        return {from: xc(b.line, c), to: xc(b.line, d)}
    }

    function Vc(a, b) {
        Fc(a.doc, xc(b, 0), Cc(a.doc, xc(b + 1, 0)))
    }

    function Yc(a, b, c, d) {
        w.defaults[a] = b, c && (Wc[a] = d ? function (a, b, d) {
            d != Zc && c(a, b, d)
        } : c)
    }

    function dd(a, b) {
        if (b === !0)return b;
        if (a.copyState)return a.copyState(b);
        var c = {};
        for (var d in b) {
            var e = b[d];
            e instanceof Array && (e = e.concat([])), c[d] = e
        }
        return c
    }

    function ed(a, b, c) {
        return a.startState ? a.startState(b, c) : !0
    }

    function hd(a) {
        return "string" == typeof a ? gd[a] : a
    }

    function id(a, b, c) {
        function d(b) {
            b = hd(b);
            var e = b[a];
            if (e === !1)return "stop";
            if (null != e && c(e))return !0;
            if (b.nofallthrough)return "stop";
            var f = b.fallthrough;
            if (null == f)return !1;
            if ("[object Array]" != Object.prototype.toString.call(f))return d(f);
            for (var g = 0, h = f.length; h > g; ++g) {
                var i = d(f[g]);
                if (i)return i
            }
            return !1
        }

        for (var e = 0; e < b.length; ++e) {
            var f = d(b[e]);
            if (f)return "stop" != f
        }
    }

    function jd(a) {
        var b = vf[a.keyCode];
        return "Ctrl" == b || "Alt" == b || "Shift" == b || "Mod" == b
    }

    function kd(a, b) {
        if (h && 34 == a.keyCode && a["char"])return !1;
        var c = vf[a.keyCode];
        return null == c || a.altGraphKey ? !1 : (a.altKey && (c = "Alt-" + c), (s ? a.metaKey : a.ctrlKey) && (c = "Ctrl-" + c), (s ? a.ctrlKey : a.metaKey) && (c = "Cmd-" + c), !b && a.shiftKey && (c = "Shift-" + c), c)
    }

    function ld(a, b) {
        this.pos = this.start = 0, this.string = a, this.tabSize = b || 8, this.lastColumnPos = this.lastColumnValue = 0
    }

    function md(a, b) {
        this.lines = [], this.type = b, this.doc = a
    }

    function nd(a, b, c, d, e) {
        if (d && d.shared)return pd(a, b, c, d, e);
        if (a.cm && !a.cm.curOp)return Db(a.cm, nd)(a, b, c, d, e);
        var f = new md(a, e);
        if ("range" == e && !zc(b, c))return f;
        d && _e(d, f), f.replacedWith && (f.collapsed = !0, f.replacedWith = gf("span", [f.replacedWith], "CodeMirror-widget"), d.handleMouseEvents || (f.replacedWith.ignoreEvents = !0)), f.collapsed && (v = !0), f.addToHistory && se(a, {
            from: b,
            to: c,
            origin: "markText"
        }, {head: a.sel.head, anchor: a.sel.anchor}, 0 / 0);
        var i, j, l, g = b.line, h = 0, k = a.cm;
        if (a.iter(g, c.line + 1, function (d) {
                k && f.collapsed && !k.options.lineWrapping && Bd(a, d) == k.display.maxLine && (l = !0);
                var e = {from: null, to: null, marker: f};
                h += d.text.length, g == b.line && (e.from = b.ch, h -= b.ch), g == c.line && (e.to = c.ch, h -= d.text.length - c.ch), f.collapsed && (g == c.line && (j = yd(d, c.ch)), g == b.line ? i = yd(d, b.ch) : ke(d, 0)), sd(d, e), ++g
            }), f.collapsed && a.iter(b.line, c.line + 1, function (b) {
                Cd(a, b) && ke(b, 0)
            }), f.clearOnEnter && He(f, "beforeCursorEnter", function () {
                f.clear()
            }), f.readOnly && (u = !0, (a.history.done.length || a.history.undone.length) && a.clearHistory()), f.collapsed) {
            if (i != j)throw new Error("Inserting collapsed marker overlapping an existing one");
            f.size = h, f.atomic = !0
        }
        return k && (l && (k.curOp.updateMaxLine = !0), (f.className || f.title || f.startStyle || f.endStyle || f.collapsed) && Gb(k, b.line, c.line + 1), f.atomic && Ic(k)), f
    }

    function od(a, b) {
        this.markers = a, this.primary = b;
        for (var c = 0, d = this; c < a.length; ++c)a[c].parent = this, He(a[c], "clear", function () {
            d.clear()
        })
    }

    function pd(a, b, c, d, e) {
        d = _e(d), d.shared = !1;
        var f = [nd(a, b, c, d, e)], g = f[0], h = d.replacedWith;
        return fe(a, function (a) {
            h && (d.replacedWith = h.cloneNode(!0)), f.push(nd(a, Cc(a, b), Cc(a, c), d, e));
            for (var i = 0; i < a.linked.length; ++i)if (a.linked[i].isParent)return;
            g = Xe(f)
        }), new od(f, g)
    }

    function qd(a, b) {
        if (a)for (var c = 0; c < a.length; ++c) {
            var d = a[c];
            if (d.marker == b)return d
        }
    }

    function rd(a, b) {
        for (var c, d = 0; d < a.length; ++d)a[d] != b && (c || (c = [])).push(a[d]);
        return c
    }

    function sd(a, b) {
        a.markedSpans = a.markedSpans ? a.markedSpans.concat([b]) : [b], b.marker.attachLine(a)
    }

    function td(a, b, c) {
        if (a)for (var e, d = 0; d < a.length; ++d) {
            var f = a[d], g = f.marker, h = null == f.from || (g.inclusiveLeft ? f.from <= b : f.from < b);
            if (h || "bookmark" == g.type && f.from == b && (!c || !f.marker.insertLeft)) {
                var i = null == f.to || (g.inclusiveRight ? f.to >= b : f.to > b);
                (e || (e = [])).push({from: f.from, to: i ? null : f.to, marker: g})
            }
        }
        return e
    }

    function ud(a, b, c) {
        if (a)for (var e, d = 0; d < a.length; ++d) {
            var f = a[d], g = f.marker, h = null == f.to || (g.inclusiveRight ? f.to >= b : f.to > b);
            if (h || "bookmark" == g.type && f.from == b && (!c || f.marker.insertLeft)) {
                var i = null == f.from || (g.inclusiveLeft ? f.from <= b : f.from < b);
                (e || (e = [])).push({from: i ? null : f.from - b, to: null == f.to ? null : f.to - b, marker: g})
            }
        }
        return e
    }

    function vd(a, b) {
        var c = Ec(a, b.from.line) && he(a, b.from.line).markedSpans, d = Ec(a, b.to.line) && he(a, b.to.line).markedSpans;
        if (!c && !d)return null;
        var e = b.from.ch, f = b.to.ch, g = yc(b.from, b.to), h = td(c, e, g), i = ud(d, f, g), j = 1 == b.text.length, k = Xe(b.text).length + (j ? e : 0);
        if (h)for (var l = 0; l < h.length; ++l) {
            var m = h[l];
            if (null == m.to) {
                var n = qd(i, m.marker);
                n ? j && (m.to = null == n.to ? null : n.to + k) : m.to = e
            }
        }
        if (i)for (var l = 0; l < i.length; ++l) {
            var m = i[l];
            if (null != m.to && (m.to += k), null == m.from) {
                var n = qd(h, m.marker);
                n || (m.from = k, j && (h || (h = [])).push(m))
            } else m.from += k, j && (h || (h = [])).push(m)
        }
        if (j && h) {
            for (var l = 0; l < h.length; ++l)null != h[l].from && h[l].from == h[l].to && "bookmark" != h[l].marker.type && h.splice(l--, 1);
            h.length || (h = null)
        }
        var o = [h];
        if (!j) {
            var q, p = b.text.length - 2;
            if (p > 0 && h)for (var l = 0; l < h.length; ++l)null == h[l].to && (q || (q = [])).push({
                from: null,
                to: null,
                marker: h[l].marker
            });
            for (var l = 0; p > l; ++l)o.push(q);
            o.push(i)
        }
        return o
    }

    function wd(a, b) {
        var c = ue(a, b), d = vd(a, b);
        if (!c)return d;
        if (!d)return c;
        for (var e = 0; e < c.length; ++e) {
            var f = c[e], g = d[e];
            if (f && g)a:for (var h = 0; h < g.length; ++h) {
                for (var i = g[h], j = 0; j < f.length; ++j)if (f[j].marker == i.marker)continue a;
                f.push(i)
            } else g && (c[e] = g)
        }
        return c
    }

    function xd(a, b, c) {
        var d = null;
        if (a.iter(b.line, c.line + 1, function (a) {
                if (a.markedSpans)for (var b = 0; b < a.markedSpans.length; ++b) {
                    var c = a.markedSpans[b].marker;
                    !c.readOnly || d && -1 != Ze(d, c) || (d || (d = [])).push(c)
                }
            }), !d)return null;
        for (var e = [{
            from: b,
            to: c
        }], f = 0; f < d.length; ++f)for (var g = d[f], h = g.find(), i = 0; i < e.length; ++i) {
            var j = e[i];
            if (!zc(j.to, h.from) && !zc(h.to, j.from)) {
                var k = [i, 1];
                (zc(j.from, h.from) || !g.inclusiveLeft && yc(j.from, h.from)) && k.push({
                    from: j.from,
                    to: h.from
                }), (zc(h.to, j.to) || !g.inclusiveRight && yc(j.to, h.to)) && k.push({
                    from: h.to,
                    to: j.to
                }), e.splice.apply(e, k), i += k.length - 1
            }
        }
        return e
    }

    function yd(a, b) {
        var d, c = v && a.markedSpans;
        if (c)for (var e, f = 0; f < c.length; ++f)e = c[f], e.marker.collapsed && (null == e.from || e.from < b) && (null == e.to || e.to > b) && (!d || d.width < e.marker.width) && (d = e.marker);
        return d
    }

    function zd(a) {
        return yd(a, -1)
    }

    function Ad(a) {
        return yd(a, a.text.length + 1)
    }

    function Bd(a, b) {
        for (var c; c = zd(b);)b = he(a, c.find().from.line);
        return b
    }

    function Cd(a, b) {
        var c = v && b.markedSpans;
        if (c)for (var d, e = 0; e < c.length; ++e)if (d = c[e], d.marker.collapsed) {
            if (null == d.from)return !0;
            if (!d.marker.replacedWith && 0 == d.from && d.marker.inclusiveLeft && Dd(a, b, d))return !0
        }
    }

    function Dd(a, b, c) {
        if (null == c.to) {
            var d = c.marker.find().to, e = he(a, d.line);
            return Dd(a, e, qd(e.markedSpans, c.marker))
        }
        if (c.marker.inclusiveRight && c.to == b.text.length)return !0;
        for (var f, g = 0; g < b.markedSpans.length; ++g)if (f = b.markedSpans[g], f.marker.collapsed && !f.marker.replacedWith && f.from == c.to && (f.marker.inclusiveLeft || c.marker.inclusiveRight) && Dd(a, b, f))return !0
    }

    function Ed(a) {
        var b = a.markedSpans;
        if (b) {
            for (var c = 0; c < b.length; ++c)b[c].marker.detachLine(a);
            a.markedSpans = null
        }
    }

    function Fd(a, b) {
        if (b) {
            for (var c = 0; c < b.length; ++c)b[c].marker.attachLine(a);
            a.markedSpans = b
        }
    }

    function Hd(a) {
        return function () {
            var b = !this.cm.curOp;
            b && Bb(this.cm);
            try {
                var c = a.apply(this, arguments)
            } finally {
                b && Cb(this.cm)
            }
            return c
        }
    }

    function Id(a) {
        return null != a.height ? a.height : (a.node.parentNode && 1 == a.node.parentNode.nodeType || jf(a.cm.display.measure, gf("div", [a.node], null, "position: relative")), a.height = a.node.offsetHeight)
    }

    function Jd(a, b, c, d) {
        var e = new Gd(a, c, d);
        return e.noHScroll && (a.display.alignWidgets = !0), Rc(a, b, function (b) {
            var c = b.widgets || (b.widgets = []);
            if (null == e.insertAt ? c.push(e) : c.splice(Math.min(c.length - 1, Math.max(0, e.insertAt)), 0, e), e.line = b, !Cd(a.doc, b) || e.showIfHidden) {
                var d = ne(a, b) < a.doc.scrollTop;
                ke(b, b.height + Id(e)), d && Pc(a, 0, e.height)
            }
            return !0
        }), e
    }

    function Ld(a, b, c, d) {
        a.text = b, a.stateAfter && (a.stateAfter = null), a.styles && (a.styles = null), null != a.order && (a.order = null), Ed(a), Fd(a, c);
        var e = d ? d(a) : 1;
        e != a.height && ke(a, e)
    }

    function Md(a) {
        a.parent = null, Ed(a)
    }

    function Nd(a, b, c, d, e) {
        var f = c.flattenSpans;
        null == f && (f = a.options.flattenSpans);
        var j, g = 0, h = null, i = new ld(b, a.options.tabSize);
        for ("" == b && c.blankLine && c.blankLine(d); !i.eol();)i.pos > a.options.maxHighlightLength ? (f = !1, i.pos = Math.min(b.length, i.start + 5e4), j = null) : j = c.token(i, d), f && h == j || (g < i.start && e(i.start, h), g = i.start, h = j), i.start = i.pos;
        g < i.pos && e(i.pos, h)
    }

    function Od(a, b, c) {
        var d = [a.state.modeGen];
        Nd(a, b.text, a.doc.mode, c, function (a, b) {
            d.push(a, b)
        });
        for (var e = 0; e < a.state.overlays.length; ++e) {
            var f = a.state.overlays[e], g = 1, h = 0;
            Nd(a, b.text, f.mode, !0, function (a, b) {
                for (var c = g; a > h;) {
                    var e = d[g];
                    e > a && d.splice(g, 1, a, d[g + 1], e), g += 2, h = Math.min(a, e)
                }
                if (b)if (f.opaque)d.splice(c, g - c, a, b), g = c + 2; else for (; g > c; c += 2) {
                    var i = d[c + 1];
                    d[c + 1] = i ? i + " " + b : b
                }
            })
        }
        return d
    }

    function Pd(a, b) {
        return b.styles && b.styles[0] == a.state.modeGen || (b.styles = Od(a, b, b.stateAfter = db(a, le(b)))), b.styles
    }

    function Qd(a, b, c) {
        var d = a.doc.mode, e = new ld(b.text, a.options.tabSize);
        for ("" == b.text && d.blankLine && d.blankLine(c); !e.eol() && e.pos <= a.options.maxHighlightLength;)d.token(e, c), e.start = e.pos
    }

    function Sd(a) {
        return a ? Rd[a] || (Rd[a] = "cm-" + a.replace(/ +/g, " cm-")) : null
    }

    function Td(a, c, d, f) {
        for (var g, h = c, i = !0; g = zd(h);)h = he(a.doc, g.find().from.line);
        var j = {pre: gf("pre"), col: 0, pos: 0, measure: null, measuredSomething: !1, cm: a, copyWidgets: f};
        h.textClass && (j.pre.className = h.textClass);
        do {
            h.text && (i = !1), j.measure = h == c && d, j.pos = 0, j.addToken = j.measure ? Wd : Vd, (b || e) && a.getOption("lineWrapping") && (j.addToken = Xd(j.addToken));
            var k = Zd(h, j, Pd(a, h));
            d && h == c && !j.measuredSomething && (d[0] = j.pre.appendChild(rf(a.display.measure)), j.measuredSomething = !0), k && (h = he(a.doc, k.to.line))
        } while (k);
        !d || j.measuredSomething || d[0] || (d[0] = j.pre.appendChild(i ? gf("span", "\xa0") : rf(a.display.measure))), j.pre.firstChild || Cd(a.doc, c) || j.pre.appendChild(document.createTextNode("\xa0"));
        var l;
        if (d && b && (l = oe(h))) {
            var m = l.length - 1;
            l[m].from == l[m].to && --m;
            var n = l[m], o = l[m - 1];
            if (n.from + 1 == n.to && o && n.level < o.level) {
                var p = d[j.pos - 1];
                p && p.parentNode.insertBefore(p.measureRight = rf(a.display.measure), p.nextSibling)
            }
        }
        return Je(a, "renderLine", a, c, j.pre), j.pre
    }

    function Vd(a, b, c, d, e, f) {
        if (b) {
            if (Ud.test(b))for (var g = document.createDocumentFragment(), h = 0; ;) {
                Ud.lastIndex = h;
                var i = Ud.exec(b), j = i ? i.index - h : b.length - h;
                if (j && (g.appendChild(document.createTextNode(b.slice(h, h + j))), a.col += j), !i)break;
                if (h += j + 1, "	" == i[0]) {
                    var k = a.cm.options.tabSize, l = k - a.col % k;
                    g.appendChild(gf("span", We(l), "cm-tab")), a.col += l
                } else {
                    var m = gf("span", "\u2022", "cm-invalidchar");
                    m.title = "\\u" + i[0].charCodeAt(0).toString(16), g.appendChild(m), a.col += 1
                }
            } else {
                a.col += b.length;
                var g = document.createTextNode(b)
            }
            if (c || d || e || a.measure) {
                var n = c || "";
                d && (n += d), e && (n += e);
                var m = gf("span", [g], n);
                return f && (m.title = f), a.pre.appendChild(m)
            }
            a.pre.appendChild(g)
        }
    }

    function Wd(a, c, d, e, f) {
        for (var g = a.cm.options.lineWrapping, h = 0; h < c.length; ++h) {
            var i = c.charAt(h), j = 0 == h;
            i >= "\ud800" && "\udbff" > i && h < c.length - 1 ? (i = c.slice(h, h + 2), ++h) : h && g && nf(c, h) && a.pre.appendChild(gf("wbr"));
            var k = a.measure[a.pos], l = a.measure[a.pos] = Vd(a, i, d, j && e, h == c.length - 1 && f);
            k && (l.leftSide = k.leftSide || k), b && g && " " == i && h && !/\s/.test(c.charAt(h - 1)) && h < c.length - 1 && !/\s/.test(c.charAt(h + 1)) && (l.style.whiteSpace = "normal"), a.pos += i.length
        }
        c.length && (a.measuredSomething = !0)
    }

    function Xd(a) {
        function b(a) {
            for (var b = " ", c = 0; c < a.length - 2; ++c)b += c % 2 ? " " : "\xa0";
            return b += " "
        }

        return function (c, d, e, f, g, h) {
            return a(c, d.replace(/ {3,}/, b), e, f, g, h)
        }
    }

    function Yd(a, b, c, d) {
        var e = !d && c.replacedWith;
        if (e && (a.copyWidgets && (e = e.cloneNode(!0)), a.pre.appendChild(e), a.measure)) {
            if (b)a.measure[a.pos] = e; else {
                var f = rf(a.cm.display.measure);
                if ("bookmark" != c.type || c.insertLeft) {
                    if (a.measure[a.pos])return;
                    a.measure[a.pos] = a.pre.insertBefore(f, e)
                } else a.measure[a.pos] = a.pre.appendChild(f)
            }
            a.measuredSomething = !0
        }
        a.pos += b
    }

    function Zd(a, b, c) {
        var d = a.markedSpans, e = a.text, f = 0;
        if (d)for (var k, m, n, o, p, q, h = e.length, i = 0, g = 1, j = "", l = 0; ;) {
            if (l == i) {
                m = n = o = p = "", q = null, l = 1 / 0;
                for (var r = [], s = 0; s < d.length; ++s) {
                    var t = d[s], u = t.marker;
                    t.from <= i && (null == t.to || t.to > i) ? (null != t.to && l > t.to && (l = t.to, n = ""), u.className && (m += " " + u.className), u.startStyle && t.from == i && (o += " " + u.startStyle), u.endStyle && t.to == l && (n += " " + u.endStyle), u.title && !p && (p = u.title), u.collapsed && (!q || q.marker.size < u.size) && (q = t)) : t.from > i && l > t.from && (l = t.from), "bookmark" == u.type && t.from == i && u.replacedWith && r.push(u)
                }
                if (q && (q.from || 0) == i && (Yd(b, (null == q.to ? h : q.to) - i, q.marker, null == q.from), null == q.to))return q.marker.find();
                if (!q && r.length)for (var s = 0; s < r.length; ++s)Yd(b, 0, r[s])
            }
            if (i >= h)break;
            for (var v = Math.min(h, l); ;) {
                if (j) {
                    var w = i + j.length;
                    if (!q) {
                        var x = w > v ? j.slice(0, v - i) : j;
                        b.addToken(b, x, k ? k + m : m, o, i + x.length == l ? n : "", p)
                    }
                    if (w >= v) {
                        j = j.slice(v - i), i = v;
                        break
                    }
                    i = w, o = ""
                }
                j = e.slice(f, f = c[g++]), k = Sd(c[g++])
            }
        } else for (var g = 1; g < c.length; g += 2)b.addToken(b, e.slice(f, f = c[g]), Sd(c[g + 1]))
    }

    function $d(a, b, c, d, e) {
        function f(a) {
            return c ? c[a] : null
        }

        function g(a, c, d) {
            Ld(a, c, d, e), Me(a, "change", a, b)
        }

        var h = b.from, i = b.to, j = b.text, k = he(a, h.line), l = he(a, i.line), m = Xe(j), n = f(j.length - 1), o = i.line - h.line;
        if (0 == h.ch && 0 == i.ch && "" == m) {
            for (var p = 0, q = j.length - 1, r = []; q > p; ++p)r.push(new Kd(j[p], f(p), e));
            g(l, l.text, n), o && a.remove(h.line, o), r.length && a.insert(h.line, r)
        } else if (k == l)if (1 == j.length)g(k, k.text.slice(0, h.ch) + m + k.text.slice(i.ch), n); else {
            for (var r = [], p = 1, q = j.length - 1; q > p; ++p)r.push(new Kd(j[p], f(p), e));
            r.push(new Kd(m + k.text.slice(i.ch), n, e)), g(k, k.text.slice(0, h.ch) + j[0], f(0)), a.insert(h.line + 1, r)
        } else if (1 == j.length)g(k, k.text.slice(0, h.ch) + j[0] + l.text.slice(i.ch), f(0)), a.remove(h.line + 1, o); else {
            g(k, k.text.slice(0, h.ch) + j[0], f(0)), g(l, m + l.text.slice(i.ch), n);
            for (var p = 1, q = j.length - 1, r = []; q > p; ++p)r.push(new Kd(j[p], f(p), e));
            o > 1 && a.remove(h.line + 1, o - 1), a.insert(h.line + 1, r)
        }
        Me(a, "change", a, b), Hc(a, d.anchor, d.head, null, !0)
    }

    function _d(a) {
        this.lines = a, this.parent = null;
        for (var b = 0, c = a.length, d = 0; c > b; ++b)a[b].parent = this, d += a[b].height;
        this.height = d
    }

    function ae(a) {
        this.children = a;
        for (var b = 0, c = 0, d = 0, e = a.length; e > d; ++d) {
            var f = a[d];
            b += f.chunkSize(), c += f.height, f.parent = this
        }
        this.size = b, this.height = c, this.parent = null
    }

    function fe(a, b, c) {
        function d(a, e, f) {
            if (a.linked)for (var g = 0; g < a.linked.length; ++g) {
                var h = a.linked[g];
                if (h.doc != e) {
                    var i = f && h.sharedHist;
                    (!c || i) && (b(h.doc, i), d(h.doc, a, i))
                }
            }
        }

        d(a, null, !0)
    }

    function ge(a, b) {
        if (b.cm)throw new Error("This document is already in use.");
        a.doc = b, b.cm = a, B(a), y(a), a.options.lineWrapping || H(a), a.options.mode = b.modeOption, Gb(a)
    }

    function he(a, b) {
        for (b -= a.first; !a.lines;)for (var c = 0; ; ++c) {
            var d = a.children[c], e = d.chunkSize();
            if (e > b) {
                a = d;
                break
            }
            b -= e
        }
        return a.lines[b]
    }

    function ie(a, b, c) {
        var d = [], e = b.line;
        return a.iter(b.line, c.line + 1, function (a) {
            var f = a.text;
            e == c.line && (f = f.slice(0, c.ch)), e == b.line && (f = f.slice(b.ch)), d.push(f), ++e
        }), d
    }

    function je(a, b, c) {
        var d = [];
        return a.iter(b, c, function (a) {
            d.push(a.text)
        }), d
    }

    function ke(a, b) {
        for (var c = b - a.height, d = a; d; d = d.parent)d.height += c
    }

    function le(a) {
        if (null == a.parent)return null;
        for (var b = a.parent, c = Ze(b.lines, a), d = b.parent; d; b = d, d = d.parent)for (var e = 0; d.children[e] != b; ++e)c += d.children[e].chunkSize();
        return c + b.first
    }

    function me(a, b) {
        var c = a.first;
        a:do {
            for (var d = 0, e = a.children.length; e > d; ++d) {
                var f = a.children[d], g = f.height;
                if (g > b) {
                    a = f;
                    continue a
                }
                b -= g, c += f.chunkSize()
            }
            return c
        } while (!a.lines);
        for (var d = 0, e = a.lines.length; e > d; ++d) {
            var h = a.lines[d], i = h.height;
            if (i > b)break;
            b -= i
        }
        return c + d
    }

    function ne(a, b) {
        b = Bd(a.doc, b);
        for (var c = 0, d = b.parent, e = 0; e < d.lines.length; ++e) {
            var f = d.lines[e];
            if (f == b)break;
            c += f.height
        }
        for (var g = d.parent; g; d = g, g = d.parent)for (var e = 0; e < g.children.length; ++e) {
            var h = g.children[e];
            if (h == d)break;
            c += h.height
        }
        return c
    }

    function oe(a) {
        var b = a.order;
        return null == b && (b = a.order = Jf(a.text)), b
    }

    function pe(a) {
        return {
            done: [],
            undone: [],
            undoDepth: 1 / 0,
            lastTime: 0,
            lastOp: null,
            lastOrigin: null,
            generation: a || 1,
            maxGeneration: a || 1
        }
    }

    function qe(a, b, c, d) {
        var e = b["spans_" + a.id], f = 0;
        a.iter(Math.max(a.first, c), Math.min(a.first + a.size, d), function (c) {
            c.markedSpans && ((e || (e = b["spans_" + a.id] = {}))[f] = c.markedSpans), ++f
        })
    }

    function re(a, b) {
        var c = {line: b.from.line, ch: b.from.ch}, d = {from: c, to: mc(b), text: ie(a, b.from, b.to)};
        return qe(a, d, b.from.line, b.to.line + 1), fe(a, function (a) {
            qe(a, d, b.from.line, b.to.line + 1)
        }, !0), d
    }

    function se(a, b, c, d) {
        var e = a.history;
        e.undone.length = 0;
        var f = +new Date, g = Xe(e.done);
        if (g && (e.lastOp == d || e.lastOrigin == b.origin && b.origin && ("+" == b.origin.charAt(0) && a.cm && e.lastTime > f - a.cm.options.historyEventDelay || "*" == b.origin.charAt(0)))) {
            var h = Xe(g.changes);
            yc(b.from, b.to) && yc(b.from, h.to) ? h.to = mc(b) : g.changes.push(re(a, b)), g.anchorAfter = c.anchor, g.headAfter = c.head
        } else for (g = {
            changes: [re(a, b)],
            generation: e.generation,
            anchorBefore: a.sel.anchor,
            headBefore: a.sel.head,
            anchorAfter: c.anchor,
            headAfter: c.head
        }, e.done.push(g), e.generation = ++e.maxGeneration; e.done.length > e.undoDepth;)e.done.shift();
        e.lastTime = f, e.lastOp = d, e.lastOrigin = b.origin
    }

    function te(a) {
        if (!a)return null;
        for (var c, b = 0; b < a.length; ++b)a[b].marker.explicitlyCleared ? c || (c = a.slice(0, b)) : c && c.push(a[b]);
        return c ? c.length ? c : null : a
    }

    function ue(a, b) {
        var c = b["spans_" + a.id];
        if (!c)return null;
        for (var d = 0, e = []; d < b.text.length; ++d)e.push(te(c[d]));
        return e
    }

    function ve(a, b) {
        for (var c = 0, d = []; c < a.length; ++c) {
            var e = a[c], f = e.changes, g = [];
            d.push({
                changes: g,
                anchorBefore: e.anchorBefore,
                headBefore: e.headBefore,
                anchorAfter: e.anchorAfter,
                headAfter: e.headAfter
            });
            for (var h = 0; h < f.length; ++h) {
                var j, i = f[h];
                if (g.push({
                        from: i.from,
                        to: i.to,
                        text: i.text
                    }), b)for (var k in i)(j = k.match(/^spans_(\d+)$/)) && Ze(b, Number(j[1])) > -1 && (Xe(g)[k] = i[k], delete i[k])
            }
        }
        return d
    }

    function we(a, b, c, d) {
        c < a.line ? a.line += d : b < a.line && (a.line = b, a.ch = 0)
    }

    function xe(a, b, c, d) {
        for (var e = 0; e < a.length; ++e) {
            for (var f = a[e], g = !0, h = 0; h < f.changes.length; ++h) {
                var i = f.changes[h];
                if (f.copied || (i.from = Ac(i.from), i.to = Ac(i.to)), c < i.from.line)i.from.line += d, i.to.line += d; else if (b <= i.to.line) {
                    g = !1;
                    break
                }
            }
            f.copied || (f.anchorBefore = Ac(f.anchorBefore), f.headBefore = Ac(f.headBefore), f.anchorAfter = Ac(f.anchorAfter), f.readAfter = Ac(f.headAfter), f.copied = !0), g ? (we(f.anchorBefore), we(f.headBefore), we(f.anchorAfter), we(f.headAfter)) : (a.splice(0, e + 1), e = 0)
        }
    }

    function ye(a, b) {
        var c = b.from.line, d = b.to.line, e = b.text.length - (d - c) - 1;
        xe(a.done, c, d, e), xe(a.undone, c, d, e)
    }

    function ze() {
        Ee(this)
    }

    function Ae(a) {
        return a.stop || (a.stop = ze), a
    }

    function Be(a) {
        a.preventDefault ? a.preventDefault() : a.returnValue = !1
    }

    function Ce(a) {
        a.stopPropagation ? a.stopPropagation() : a.cancelBubble = !0
    }

    function De(a) {
        return null != a.defaultPrevented ? a.defaultPrevented : 0 == a.returnValue
    }

    function Ee(a) {
        Be(a), Ce(a)
    }

    function Fe(a) {
        return a.target || a.srcElement
    }

    function Ge(a) {
        var b = a.which;
        return null == b && (1 & a.button ? b = 1 : 2 & a.button ? b = 3 : 4 & a.button && (b = 2)), p && a.ctrlKey && 1 == b && (b = 3), b
    }

    function He(a, b, c) {
        if (a.addEventListener)a.addEventListener(b, c, !1); else if (a.attachEvent)a.attachEvent("on" + b, c); else {
            var d = a._handlers || (a._handlers = {}), e = d[b] || (d[b] = []);
            e.push(c)
        }
    }

    function Ie(a, b, c) {
        if (a.removeEventListener)a.removeEventListener(b, c, !1); else if (a.detachEvent)a.detachEvent("on" + b, c); else {
            var d = a._handlers && a._handlers[b];
            if (!d)return;
            for (var e = 0; e < d.length; ++e)if (d[e] == c) {
                d.splice(e, 1);
                break
            }
        }
    }

    function Je(a, b) {
        var c = a._handlers && a._handlers[b];
        if (c)for (var d = Array.prototype.slice.call(arguments, 2), e = 0; e < c.length; ++e)c[e].apply(null, d)
    }

    function Me(a, b) {
        function e(a) {
            return function () {
                a.apply(null, d)
            }
        }

        var c = a._handlers && a._handlers[b];
        if (c) {
            var d = Array.prototype.slice.call(arguments, 2);
            Ke || (++Le, Ke = [], setTimeout(Oe, 0));
            for (var f = 0; f < c.length; ++f)Ke.push(e(c[f]))
        }
    }

    function Ne(a, b, c) {
        return Je(a, c || b.type, a, b), De(b) || b.codemirrorIgnore
    }

    function Oe() {
        --Le;
        var a = Ke;
        Ke = null;
        for (var b = 0; b < a.length; ++b)a[b]()
    }

    function Pe(a, b) {
        var c = a._handlers && a._handlers[b];
        return c && c.length > 0
    }

    function Qe(a) {
        a.prototype.on = function (a, b) {
            He(this, a, b)
        }, a.prototype.off = function (a, b) {
            Ie(this, a, b)
        }
    }

    function Te() {
        this.id = null
    }

    function Ue(a, b, c, d, e) {
        null == b && (b = a.search(/[^\s\u00a0]/), -1 == b && (b = a.length));
        for (var f = d || 0, g = e || 0; b > f; ++f)"	" == a.charAt(f) ? g += c - g % c : ++g;
        return g
    }

    function We(a) {
        for (; Ve.length <= a;)Ve.push(Xe(Ve) + " ");
        return Ve[a]
    }

    function Xe(a) {
        return a[a.length - 1]
    }

    function Ye(a) {
        if (n)a.selectionStart = 0, a.selectionEnd = a.value.length; else try {
            a.select()
        } catch (b) {
        }
    }

    function Ze(a, b) {
        if (a.indexOf)return a.indexOf(b);
        for (var c = 0, d = a.length; d > c; ++c)if (a[c] == b)return c;
        return -1
    }

    function $e(a, b) {
        function c() {
        }

        c.prototype = a;
        var d = new c;
        return b && _e(b, d), d
    }

    function _e(a, b) {
        b || (b = {});
        for (var c in a)a.hasOwnProperty(c) && (b[c] = a[c]);
        return b
    }

    function af(a) {
        for (var b = [], c = 0; a > c; ++c)b.push(void 0);
        return b
    }

    function bf(a) {
        var b = Array.prototype.slice.call(arguments, 1);
        return function () {
            return a.apply(null, b)
        }
    }

    function df(a) {
        return /\w/.test(a) || a > "\x80" && (a.toUpperCase() != a.toLowerCase() || cf.test(a))
    }

    function ef(a) {
        for (var b in a)if (a.hasOwnProperty(b) && a[b])return !1;
        return !0
    }

    function gf(a, b, c, d) {
        var e = document.createElement(a);
        if (c && (e.className = c), d && (e.style.cssText = d), "string" == typeof b)kf(e, b); else if (b)for (var f = 0; f < b.length; ++f)e.appendChild(b[f]);
        return e
    }

    function hf(a) {
        for (var b = a.childNodes.length; b > 0; --b)a.removeChild(a.firstChild);
        return a
    }

    function jf(a, b) {
        return hf(a).appendChild(b)
    }

    function kf(a, b) {
        d ? (a.innerHTML = "", a.appendChild(document.createTextNode(b))) : a.textContent = b
    }

    function lf(a) {
        return a.getBoundingClientRect()
    }

    function nf() {
        return !1
    }

    function pf(a) {
        if (null != of)return of;
        var b = gf("div", null, null, "width: 50px; height: 50px; overflow-x: scroll");
        return jf(a, b), b.offsetWidth && (of = b.offsetHeight - b.clientHeight), of || 0
    }

    function rf(a) {
        if (null == qf) {
            var b = gf("span", "\u200b");
            jf(a, gf("span", [b, document.createTextNode("x")])), 0 != a.firstChild.offsetHeight && (qf = b.offsetWidth <= 1 && b.offsetHeight > 2 && !c)
        }
        return qf ? gf("span", "\u200b") : gf("span", "\xa0", null, "display: inline-block; width: 1px; margin-right: -1px")
    }

    function wf(a, b, c, d) {
        if (!a)return d(b, c, "ltr");
        for (var e = !1, f = 0; f < a.length; ++f) {
            var g = a[f];
            (g.from < c && g.to > b || b == c && g.to == b) && (d(Math.max(g.from, b), Math.min(g.to, c), 1 == g.level ? "rtl" : "ltr"), e = !0)
        }
        e || d(b, c, "ltr")
    }

    function xf(a) {
        return a.level % 2 ? a.to : a.from
    }

    function yf(a) {
        return a.level % 2 ? a.from : a.to
    }

    function zf(a) {
        var b = oe(a);
        return b ? xf(b[0]) : 0
    }

    function Af(a) {
        var b = oe(a);
        return b ? yf(Xe(b)) : a.text.length
    }

    function Bf(a, b) {
        var c = he(a.doc, b), d = Bd(a.doc, c);
        d != c && (b = le(d));
        var e = oe(d), f = e ? e[0].level % 2 ? Af(d) : zf(d) : 0;
        return xc(b, f)
    }

    function Cf(a, b) {
        for (var c, d; c = Ad(d = he(a.doc, b));)b = c.find().to.line;
        var e = oe(d), f = e ? e[0].level % 2 ? zf(d) : Af(d) : d.text.length;
        return xc(b, f)
    }

    function Df(a, b, c) {
        var d = a[0].level;
        return b == d ? !0 : c == d ? !1 : c > b
    }

    function Ff(a, b) {
        for (var d, c = 0; c < a.length; ++c) {
            var e = a[c];
            if (e.from < b && e.to > b)return Ef = null, c;
            if (e.from == b || e.to == b) {
                if (null != d)return Df(a, e.level, a[d].level) ? (Ef = d, c) : (Ef = c, d);
                d = c
            }
        }
        return Ef = null, d
    }

    function Gf(a, b, c, d) {
        if (!d)return b + c;
        do b += c; while (b > 0 && ff.test(a.text.charAt(b)));
        return b
    }

    function Hf(a, b, c, d) {
        var e = oe(a);
        if (!e)return If(a, b, c, d);
        for (var f = Ff(e, b), g = e[f], h = Gf(a, b, g.level % 2 ? -c : c, d); ;) {
            if (h > g.from && h < g.to)return h;
            if (h == g.from || h == g.to)return Ff(e, h) == f ? h : (g = e[f += c], c > 0 == g.level % 2 ? g.to : g.from);
            if (g = e[f += c], !g)return null;
            h = c > 0 == g.level % 2 ? Gf(a, g.to, -1, d) : Gf(a, g.from, 1, d)
        }
    }

    function If(a, b, c, d) {
        var e = b + c;
        if (d)for (; e > 0 && ff.test(a.text.charAt(e));)e += c;
        return 0 > e || e > a.text.length ? null : e
    }

    var a = /gecko\/\d/i.test(navigator.userAgent), b = /MSIE \d/.test(navigator.userAgent), c = b && (null == document.documentMode || document.documentMode < 8), d = b && (null == document.documentMode || document.documentMode < 9), e = /WebKit\//.test(navigator.userAgent), f = e && /Qt\/\d+\.\d+/.test(navigator.userAgent), g = /Chrome\//.test(navigator.userAgent), h = /Opera\//.test(navigator.userAgent), i = /Apple Computer/.test(navigator.vendor), j = /KHTML\//.test(navigator.userAgent), k = /Mac OS X 1\d\D([7-9]|\d\d)\D/.test(navigator.userAgent), l = /Mac OS X 1\d\D([8-9]|\d\d)\D/.test(navigator.userAgent), m = /PhantomJS/.test(navigator.userAgent), n = /AppleWebKit/.test(navigator.userAgent) && /Mobile\/\w+/.test(navigator.userAgent), o = n || /Android|webOS|BlackBerry|Opera Mini|Opera Mobi|IEMobile/i.test(navigator.userAgent), p = n || /Mac/.test(navigator.platform), q = /win/i.test(navigator.platform), r = h && navigator.userAgent.match(/Version\/(\d*\.\d*)/);
    r && (r = Number(r[1])), r && r >= 15 && (h = !1, e = !0);
    var xb, Qb, Rb, s = p && (f || h && (null == r || 12.11 > r)), t = a || b && !d, u = !1, v = !1, Ab = 0, Ub = 0, Zb = 0, $b = null;
    b ? $b = -.53 : a ? $b = 15 : g ? $b = -.7 : i && ($b = -1 / 3);
    var cc, kc, fc = null, mc = w.changeEnd = function (a) {
        return a.text ? xc(a.from.line + a.text.length - 1, Xe(a.text).length + (1 == a.text.length ? a.from.ch : 0)) : a.to
    };
    w.Pos = xc, w.prototype = {
        constructor: w, focus: function () {
            window.focus(), Lb(this), ic(this), Ib(this)
        }, setOption: function (a, b) {
            var c = this.options, d = c[a];
            (c[a] != b || "mode" == a) && (c[a] = b, Wc.hasOwnProperty(a) && Db(this, Wc[a])(this, b, d))
        }, getOption: function (a) {
            return this.options[a]
        }, getDoc: function () {
            return this.doc
        }, addKeyMap: function (a, b) {
            this.state.keyMaps[b ? "push" : "unshift"](a)
        }, removeKeyMap: function (a) {
            for (var b = this.state.keyMaps, c = 0; c < b.length; ++c)if (b[c] == a || "string" != typeof b[c] && b[c].name == a)return b.splice(c, 1), !0
        }, addOverlay: Db(null, function (a, b) {
            var c = a.token ? a : w.getMode(this.options, a);
            if (c.startState)throw new Error("Overlays may not be stateful.");
            this.state.overlays.push({mode: c, modeSpec: a, opaque: b && b.opaque}), this.state.modeGen++, Gb(this)
        }), removeOverlay: Db(null, function (a) {
            for (var b = this.state.overlays, c = 0; c < b.length; ++c) {
                var d = b[c].modeSpec;
                if (d == a || "string" == typeof a && d.name == a)return b.splice(c, 1), this.state.modeGen++, Gb(this), void 0
            }
        }), indentLine: Db(null, function (a, b, c) {
            "string" != typeof b && "number" != typeof b && (b = null == b ? this.options.smartIndent ? "smart" : "prev" : b ? "add" : "subtract"), Ec(this.doc, a) && Qc(this, a, b, c)
        }), indentSelection: Db(null, function (a) {
            var b = this.doc.sel;
            if (yc(b.from, b.to))return Qc(this, b.from.line, a);
            for (var c = b.to.line - (b.to.ch ? 0 : 1), d = b.from.line; c >= d; ++d)Qc(this, d, a)
        }), getTokenAt: function (a, b) {
            var c = this.doc;
            a = Cc(c, a);
            for (var d = db(this, a.line, b), e = this.doc.mode, f = he(c, a.line), g = new ld(f.text, this.options.tabSize); g.pos < a.ch && !g.eol();) {
                g.start = g.pos;
                var h = e.token(g, d)
            }
            return {start: g.start, end: g.pos, string: g.current(), className: h || null, type: h || null, state: d}
        }, getTokenTypeAt: function (a) {
            a = Cc(this.doc, a);
            var b = Pd(this, he(this.doc, a.line)), c = 0, d = (b.length - 1) / 2, e = a.ch;
            if (0 == e)return b[2];
            for (; ;) {
                var f = c + d >> 1;
                if ((f ? b[2 * f - 1] : 0) >= e)d = f; else {
                    if (!(b[2 * f + 1] < e))return b[2 * f + 2];
                    c = f + 1
                }
            }
        }, getModeAt: function (a) {
            var b = this.doc.mode;
            return b.innerMode ? w.innerMode(b, this.getTokenAt(a).state).mode : b
        }, getHelper: function (a, b) {
            if (cd.hasOwnProperty(b)) {
                var c = cd[b], d = this.getModeAt(a);
                return d[b] && c[d[b]] || d.helperType && c[d.helperType] || c[d.name]
            }
        }, getStateAfter: function (a, b) {
            var c = this.doc;
            return a = Bc(c, null == a ? c.first + c.size - 1 : a), db(this, a + 1, b)
        }, cursorCoords: function (a, b) {
            var c, d = this.doc.sel;
            return c = null == a ? d.head : "object" == typeof a ? Cc(this.doc, a) : a ? d.from : d.to, tb(this, c, b || "page")
        }, charCoords: function (a, b) {
            return sb(this, Cc(this.doc, a), b || "page")
        }, coordsChar: function (a, b) {
            return a = rb(this, a, b || "page"), vb(this, a.left, a.top)
        }, lineAtHeight: function (a, b) {
            return a = rb(this, {top: a, left: 0}, b || "page").top, me(this.doc, a + this.display.viewOffset)
        }, heightAtLine: function (a, b) {
            var c = !1, d = this.doc.first + this.doc.size - 1;
            a < this.doc.first ? a = this.doc.first : a > d && (a = d, c = !0);
            var e = he(this.doc, a);
            return qb(this, he(this.doc, a), {top: 0, left: 0}, b || "page").top + (c ? e.height : 0)
        }, defaultTextHeight: function () {
            return yb(this.display)
        }, defaultCharWidth: function () {
            return zb(this.display)
        }, setGutterMarker: Db(null, function (a, b, c) {
            return Rc(this, a, function (a) {
                var d = a.gutterMarkers || (a.gutterMarkers = {});
                return d[b] = c, !c && ef(d) && (a.gutterMarkers = null), !0
            })
        }), clearGutter: Db(null, function (a) {
            var b = this, c = b.doc, d = c.first;
            c.iter(function (c) {
                c.gutterMarkers && c.gutterMarkers[a] && (c.gutterMarkers[a] = null, Gb(b, d, d + 1), ef(c.gutterMarkers) && (c.gutterMarkers = null)), ++d
            })
        }), addLineClass: Db(null, function (a, b, c) {
            return Rc(this, a, function (a) {
                var d = "text" == b ? "textClass" : "background" == b ? "bgClass" : "wrapClass";
                if (a[d]) {
                    if (new RegExp("(?:^|\\s)" + c + "(?:$|\\s)").test(a[d]))return !1;
                    a[d] += " " + c
                } else a[d] = c;
                return !0
            })
        }), removeLineClass: Db(null, function (a, b, c) {
            return Rc(this, a, function (a) {
                var d = "text" == b ? "textClass" : "background" == b ? "bgClass" : "wrapClass", e = a[d];
                if (!e)return !1;
                if (null == c)a[d] = null; else {
                    var f = e.match(new RegExp("(?:^|\\s+)" + c + "(?:$|\\s+)"));
                    if (!f)return !1;
                    var g = f.index + f[0].length;
                    a[d] = e.slice(0, f.index) + (f.index && g != e.length ? " " : "") + e.slice(g) || null
                }
                return !0
            })
        }), addLineWidget: Db(null, function (a, b, c) {
            return Jd(this, a, b, c)
        }), removeLineWidget: function (a) {
            a.clear()
        }, lineInfo: function (a) {
            if ("number" == typeof a) {
                if (!Ec(this.doc, a))return null;
                var b = a;
                if (a = he(this.doc, a), !a)return null
            } else {
                var b = le(a);
                if (null == b)return null
            }
            return {
                line: b,
                handle: a,
                text: a.text,
                gutterMarkers: a.gutterMarkers,
                textClass: a.textClass,
                bgClass: a.bgClass,
                wrapClass: a.wrapClass,
                widgets: a.widgets
            }
        }, getViewport: function () {
            return {from: this.display.showingFrom, to: this.display.showingTo}
        }, addWidget: function (a, b, c, d, e) {
            var f = this.display;
            a = tb(this, Cc(this.doc, a));
            var g = a.bottom, h = a.left;
            if (b.style.position = "absolute", f.sizer.appendChild(b), "over" == d)g = a.top; else if ("above" == d || "near" == d) {
                var i = Math.max(f.wrapper.clientHeight, this.doc.height), j = Math.max(f.sizer.clientWidth, f.lineSpace.clientWidth);
                ("above" == d || a.bottom + b.offsetHeight > i) && a.top > b.offsetHeight ? g = a.top - b.offsetHeight : a.bottom + b.offsetHeight <= i && (g = a.bottom), h + b.offsetWidth > j && (h = j - b.offsetWidth)
            }
            b.style.top = g + "px", b.style.left = b.style.right = "", "right" == e ? (h = f.sizer.clientWidth - b.offsetWidth, b.style.right = "0px") : ("left" == e ? h = 0 : "middle" == e && (h = (f.sizer.clientWidth - b.offsetWidth) / 2), b.style.left = h + "px"), c && Mc(this, h, g, h + b.offsetWidth, g + b.offsetHeight)
        }, triggerOnKeyDown: Db(null, gc), execCommand: function (a) {
            return fd[a](this)
        }, findPosH: function (a, b, c, d) {
            var e = 1;
            0 > b && (e = -1, b = -b);
            for (var f = 0, g = Cc(this.doc, a); b > f && (g = Sc(this.doc, g, e, c, d), !g.hitSide); ++f);
            return g
        }, moveH: Db(null, function (a, b) {
            var d, c = this.doc.sel;
            d = c.shift || c.extend || yc(c.from, c.to) ? Sc(this.doc, c.head, a, b, this.options.rtlMoveVisually) : 0 > a ? c.from : c.to, Fc(this.doc, d, d, a)
        }), deleteH: Db(null, function (a, b) {
            var c = this.doc.sel;
            yc(c.from, c.to) ? wc(this.doc, "", c.from, Sc(this.doc, c.head, a, b, !1), "+delete") : wc(this.doc, "", c.from, c.to, "+delete"), this.curOp.userSelChange = !0
        }), findPosV: function (a, b, c, d) {
            var e = 1, f = d;
            0 > b && (e = -1, b = -b);
            for (var g = 0, h = Cc(this.doc, a); b > g; ++g) {
                var i = tb(this, h, "div");
                if (null == f ? f = i.left : i.left = f, h = Tc(this, i, e, c), h.hitSide)break
            }
            return h
        }, moveV: Db(null, function (a, b) {
            var c = this.doc.sel, d = tb(this, c.head, "div");
            null != c.goalColumn && (d.left = c.goalColumn);
            var e = Tc(this, d, a, b);
            "page" == b && Pc(this, 0, sb(this, e, "div").top - d.top), Fc(this.doc, e, e, a), c.goalColumn = d.left
        }), toggleOverwrite: function (a) {
            (null == a || a != this.state.overwrite) && ((this.state.overwrite = !this.state.overwrite) ? this.display.cursor.className += " CodeMirror-overwrite" : this.display.cursor.className = this.display.cursor.className.replace(" CodeMirror-overwrite", ""))
        }, hasFocus: function () {
            return this.state.focused
        }, scrollTo: Db(null, function (a, b) {
            Oc(this, a, b)
        }), getScrollInfo: function () {
            var a = this.display.scroller, b = Re;
            return {
                left: a.scrollLeft,
                top: a.scrollTop,
                height: a.scrollHeight - b,
                width: a.scrollWidth - b,
                clientHeight: a.clientHeight - b,
                clientWidth: a.clientWidth - b
            }
        }, scrollIntoView: Db(null, function (a, b) {
            "number" == typeof a && (a = xc(a, 0)), b || (b = 0);
            var c = a;
            a && null == a.line || (this.curOp.scrollToPos = a ? Cc(this.doc, a) : this.doc.sel.head, this.curOp.scrollToPosMargin = b, c = tb(this, this.curOp.scrollToPos));
            var d = Nc(this, c.left, c.top - b, c.right, c.bottom + b);
            Oc(this, d.scrollLeft, d.scrollTop)
        }), setSize: Db(null, function (a, b) {
            function c(a) {
                return "number" == typeof a || /^\d+$/.test(String(a)) ? a + "px" : a
            }

            null != a && (this.display.wrapper.style.width = c(a)), null != b && (this.display.wrapper.style.height = c(b)), this.options.lineWrapping && (this.display.measureLineCache.length = this.display.measureLineCachePos = 0), this.curOp.forceUpdate = !0
        }), operation: function (a) {
            return Fb(this, a)
        }, refresh: Db(null, function () {
            nb(this), Oc(this, this.doc.scrollLeft, this.doc.scrollTop), Gb(this)
        }), swapDoc: Db(null, function (a) {
            var b = this.doc;
            return b.cm = null, ge(this, a), nb(this), Kb(this, !0), Oc(this, a.scrollLeft, a.scrollTop), b
        }), getInputField: function () {
            return this.display.input
        }, getWrapperElement: function () {
            return this.display.wrapper
        }, getScrollerElement: function () {
            return this.display.scroller
        }, getGutterElement: function () {
            return this.display.gutters
        }
    }, Qe(w);
    var Wc = w.optionHandlers = {}, Xc = w.defaults = {}, Zc = w.Init = {
        toString: function () {
            return "CodeMirror.Init"
        }
    };
    Yc("value", "", function (a, b) {
        a.setValue(b)
    }, !0), Yc("mode", null, function (a, b) {
        a.doc.modeOption = b, y(a)
    }, !0), Yc("indentUnit", 2, y, !0), Yc("indentWithTabs", !1), Yc("smartIndent", !0), Yc("tabSize", 4, function (a) {
        y(a), nb(a), Gb(a)
    }, !0), Yc("electricChars", !0), Yc("rtlMoveVisually", !q), Yc("theme", "default", function (a) {
        D(a), E(a)
    }, !0), Yc("keyMap", "default", C), Yc("extraKeys", null), Yc("onKeyEvent", null), Yc("onDragEvent", null), Yc("lineWrapping", !1, z, !0), Yc("gutters", [], function (a) {
        I(a.options), E(a)
    }, !0), Yc("fixedGutter", !0, function (a, b) {
        a.display.gutters.style.left = b ? O(a.display) + "px" : "0", a.refresh()
    }, !0), Yc("coverGutterNextToScrollbar", !1, J, !0), Yc("lineNumbers", !1, function (a) {
        I(a.options), E(a)
    }, !0), Yc("firstLineNumber", 1, E, !0), Yc("lineNumberFormatter", function (a) {
        return a
    }, E, !0), Yc("showCursorWhenSelecting", !1, Y, !0), Yc("readOnly", !1, function (a, b) {
        "nocursor" == b ? (jc(a), a.display.input.blur()) : b || Kb(a, !0)
    }), Yc("dragDrop", !0), Yc("cursorBlinkRate", 530), Yc("cursorScrollMargin", 0), Yc("cursorHeight", 1), Yc("workTime", 100), Yc("workDelay", 100), Yc("flattenSpans", !0), Yc("pollInterval", 100), Yc("undoDepth", 40, function (a, b) {
        a.doc.history.undoDepth = b
    }), Yc("historyEventDelay", 500), Yc("viewportMargin", 10, function (a) {
        a.refresh()
    }, !0), Yc("maxHighlightLength", 1e4, function (a) {
        y(a), a.refresh()
    }, !0), Yc("moveInputWithCursor", !0, function (a, b) {
        b || (a.display.inputDiv.style.top = a.display.inputDiv.style.left = 0)
    }), Yc("tabindex", null, function (a, b) {
        a.display.input.tabIndex = b || ""
    }), Yc("autofocus", null);
    var $c = w.modes = {}, _c = w.mimeModes = {};
    w.defineMode = function (a, b) {
        if (w.defaults.mode || "null" == a || (w.defaults.mode = a), arguments.length > 2) {
            b.dependencies = [];
            for (var c = 2; c < arguments.length; ++c)b.dependencies.push(arguments[c])
        }
        $c[a] = b
    }, w.defineMIME = function (a, b) {
        _c[a] = b
    }, w.resolveMode = function (a) {
        if ("string" == typeof a && _c.hasOwnProperty(a))a = _c[a]; else if (a && "string" == typeof a.name && _c.hasOwnProperty(a.name)) {
            var b = _c[a.name];
            a = $e(b, a), a.name = b.name
        } else if ("string" == typeof a && /^[\w\-]+\/[\w\-]+\+xml$/.test(a))return w.resolveMode("application/xml");
        return "string" == typeof a ? {name: a} : a || {name: "null"}
    }, w.getMode = function (a, b) {
        var b = w.resolveMode(b), c = $c[b.name];
        if (!c)return w.getMode(a, "text/plain");
        var d = c(a, b);
        if (ad.hasOwnProperty(b.name)) {
            var e = ad[b.name];
            for (var f in e)e.hasOwnProperty(f) && (d.hasOwnProperty(f) && (d["_" + f] = d[f]), d[f] = e[f])
        }
        return d.name = b.name, d
    }, w.defineMode("null", function () {
        return {
            token: function (a) {
                a.skipToEnd()
            }
        }
    }), w.defineMIME("text/plain", "null");
    var ad = w.modeExtensions = {};
    w.extendMode = function (a, b) {
        var c = ad.hasOwnProperty(a) ? ad[a] : ad[a] = {};
        _e(b, c)
    }, w.defineExtension = function (a, b) {
        w.prototype[a] = b
    }, w.defineDocExtension = function (a, b) {
        ce.prototype[a] = b
    }, w.defineOption = Yc;
    var bd = [];
    w.defineInitHook = function (a) {
        bd.push(a)
    };
    var cd = w.helpers = {};
    w.registerHelper = function (a, b, c) {
        cd.hasOwnProperty(a) || (cd[a] = w[a] = {}), cd[a][b] = c
    }, w.isWordChar = df, w.copyState = dd, w.startState = ed, w.innerMode = function (a, b) {
        for (; a.innerMode;) {
            var c = a.innerMode(b);
            if (!c || c.mode == a)break;
            b = c.state, a = c.mode
        }
        return c || {mode: a, state: b}
    };
    var fd = w.commands = {
        selectAll: function (a) {
            a.setSelection(xc(a.firstLine(), 0), xc(a.lastLine()))
        }, killLine: function (a) {
            var b = a.getCursor(!0), c = a.getCursor(!1), d = !yc(b, c);
            d || a.getLine(b.line).length != b.ch ? a.replaceRange("", b, d ? c : xc(b.line), "+delete") : a.replaceRange("", b, xc(b.line + 1, 0), "+delete")
        }, deleteLine: function (a) {
            var b = a.getCursor().line;
            a.replaceRange("", xc(b, 0), xc(b), "+delete")
        }, delLineLeft: function (a) {
            var b = a.getCursor();
            a.replaceRange("", xc(b.line, 0), b, "+delete")
        }, undo: function (a) {
            a.undo()
        }, redo: function (a) {
            a.redo()
        }, goDocStart: function (a) {
            a.extendSelection(xc(a.firstLine(), 0))
        }, goDocEnd: function (a) {
            a.extendSelection(xc(a.lastLine()))
        }, goLineStart: function (a) {
            a.extendSelection(Bf(a, a.getCursor().line))
        }, goLineStartSmart: function (a) {
            var b = a.getCursor(), c = Bf(a, b.line), d = a.getLineHandle(c.line), e = oe(d);
            if (e && 0 != e[0].level)a.extendSelection(c); else {
                var f = Math.max(0, d.text.search(/\S/)), g = b.line == c.line && b.ch <= f && b.ch;
                a.extendSelection(xc(c.line, g ? 0 : f))
            }
        }, goLineEnd: function (a) {
            a.extendSelection(Cf(a, a.getCursor().line))
        }, goLineRight: function (a) {
            var b = a.charCoords(a.getCursor(), "div").top + 5;
            a.extendSelection(a.coordsChar({left: a.display.lineDiv.offsetWidth + 100, top: b}, "div"))
        }, goLineLeft: function (a) {
            var b = a.charCoords(a.getCursor(), "div").top + 5;
            a.extendSelection(a.coordsChar({left: 0, top: b}, "div"))
        }, goLineUp: function (a) {
            a.moveV(-1, "line")
        }, goLineDown: function (a) {
            a.moveV(1, "line")
        }, goPageUp: function (a) {
            a.moveV(-1, "page")
        }, goPageDown: function (a) {
            a.moveV(1, "page")
        }, goCharLeft: function (a) {
            a.moveH(-1, "char")
        }, goCharRight: function (a) {
            a.moveH(1, "char")
        }, goColumnLeft: function (a) {
            a.moveH(-1, "column")
        }, goColumnRight: function (a) {
            a.moveH(1, "column")
        }, goWordLeft: function (a) {
            a.moveH(-1, "word")
        }, goGroupRight: function (a) {
            a.moveH(1, "group")
        }, goGroupLeft: function (a) {
            a.moveH(-1, "group")
        }, goWordRight: function (a) {
            a.moveH(1, "word")
        }, delCharBefore: function (a) {
            a.deleteH(-1, "char")
        }, delCharAfter: function (a) {
            a.deleteH(1, "char")
        }, delWordBefore: function (a) {
            a.deleteH(-1, "word")
        }, delWordAfter: function (a) {
            a.deleteH(1, "word")
        }, delGroupBefore: function (a) {
            a.deleteH(-1, "group")
        }, delGroupAfter: function (a) {
            a.deleteH(1, "group")
        }, indentAuto: function (a) {
            a.indentSelection("smart")
        }, indentMore: function (a) {
            a.indentSelection("add")
        }, indentLess: function (a) {
            a.indentSelection("subtract")
        }, insertTab: function (a) {
            a.replaceSelection("	", "end", "+input")
        }, defaultTab: function (a) {
            a.somethingSelected() ? a.indentSelection("add") : a.replaceSelection("	", "end", "+input")
        }, transposeChars: function (a) {
            var b = a.getCursor(), c = a.getLine(b.line);
            b.ch > 0 && b.ch < c.length - 1 && a.replaceRange(c.charAt(b.ch) + c.charAt(b.ch - 1), xc(b.line, b.ch - 1), xc(b.line, b.ch + 1))
        }, newlineAndIndent: function (a) {
            Db(a, function () {
                a.replaceSelection("\n", "end", "+input"), a.indentLine(a.getCursor().line, null, !0)
            })()
        }, toggleOverwrite: function (a) {
            a.toggleOverwrite()
        }
    }, gd = w.keyMap = {};
    gd.basic = {
        Left: "goCharLeft",
        Right: "goCharRight",
        Up: "goLineUp",
        Down: "goLineDown",
        End: "goLineEnd",
        Home: "goLineStartSmart",
        PageUp: "goPageUp",
        PageDown: "goPageDown",
        Delete: "delCharAfter",
        Backspace: "delCharBefore",
        Tab: "defaultTab",
        "Shift-Tab": "indentAuto",
        Enter: "newlineAndIndent",
        Insert: "toggleOverwrite"
    }, gd.pcDefault = {
        "Ctrl-A": "selectAll",
        "Ctrl-D": "deleteLine",
        "Ctrl-Z": "undo",
        "Shift-Ctrl-Z": "redo",
        "Ctrl-Y": "redo",
        "Ctrl-Home": "goDocStart",
        "Alt-Up": "goDocStart",
        "Ctrl-End": "goDocEnd",
        "Ctrl-Down": "goDocEnd",
        "Ctrl-Left": "goGroupLeft",
        "Ctrl-Right": "goGroupRight",
        "Alt-Left": "goLineStart",
        "Alt-Right": "goLineEnd",
        "Ctrl-Backspace": "delGroupBefore",
        "Ctrl-Delete": "delGroupAfter",
        "Ctrl-S": "save",
        "Ctrl-F": "find",
        "Ctrl-G": "findNext",
        "Shift-Ctrl-G": "findPrev",
        "Shift-Ctrl-F": "replace",
        "Shift-Ctrl-R": "replaceAll",
        "Ctrl-[": "indentLess",
        "Ctrl-]": "indentMore",
        fallthrough: "basic"
    }, gd.macDefault = {
        "Cmd-A": "selectAll",
        "Cmd-D": "deleteLine",
        "Cmd-Z": "undo",
        "Shift-Cmd-Z": "redo",
        "Cmd-Y": "redo",
        "Cmd-Up": "goDocStart",
        "Cmd-End": "goDocEnd",
        "Cmd-Down": "goDocEnd",
        "Alt-Left": "goGroupLeft",
        "Alt-Right": "goGroupRight",
        "Cmd-Left": "goLineStart",
        "Cmd-Right": "goLineEnd",
        "Alt-Backspace": "delGroupBefore",
        "Ctrl-Alt-Backspace": "delGroupAfter",
        "Alt-Delete": "delGroupAfter",
        "Cmd-S": "save",
        "Cmd-F": "find",
        "Cmd-G": "findNext",
        "Shift-Cmd-G": "findPrev",
        "Cmd-Alt-F": "replace",
        "Shift-Cmd-Alt-F": "replaceAll",
        "Cmd-[": "indentLess",
        "Cmd-]": "indentMore",
        "Cmd-Backspace": "delLineLeft",
        fallthrough: ["basic", "emacsy"]
    }, gd["default"] = p ? gd.macDefault : gd.pcDefault, gd.emacsy = {
        "Ctrl-F": "goCharRight",
        "Ctrl-B": "goCharLeft",
        "Ctrl-P": "goLineUp",
        "Ctrl-N": "goLineDown",
        "Alt-F": "goWordRight",
        "Alt-B": "goWordLeft",
        "Ctrl-A": "goLineStart",
        "Ctrl-E": "goLineEnd",
        "Ctrl-V": "goPageDown",
        "Shift-Ctrl-V": "goPageUp",
        "Ctrl-D": "delCharAfter",
        "Ctrl-H": "delCharBefore",
        "Alt-D": "delWordAfter",
        "Alt-Backspace": "delWordBefore",
        "Ctrl-K": "killLine",
        "Ctrl-T": "transposeChars"
    }, w.lookupKey = id, w.isModifierKey = jd, w.keyName = kd, w.fromTextArea = function (a, b) {
        function e() {
            a.value = i.getValue()
        }

        if (b || (b = {}), b.value = a.value, !b.tabindex && a.tabindex && (b.tabindex = a.tabindex), !b.placeholder && a.placeholder && (b.placeholder = a.placeholder), null == b.autofocus) {
            var c = document.body;
            try {
                c = document.activeElement
            } catch (d) {
            }
            b.autofocus = c == a || null != a.getAttribute("autofocus") && c == document.body
        }
        if (a.form && (He(a.form, "submit", e), !b.leaveSubmitMethodAlone)) {
            var f = a.form, g = f.submit;
            try {
                var h = f.submit = function () {
                    e(), f.submit = g, f.submit(), f.submit = h
                }
            } catch (d) {
            }
        }
        a.style.display = "none";
        var i = w(function (b) {
            a.parentNode.insertBefore(b, a.nextSibling)
        }, b);
        return i.save = e, i.getTextArea = function () {
            return a
        }, i.toTextArea = function () {
            e(), a.parentNode.removeChild(i.getWrapperElement()), a.style.display = "", a.form && (Ie(a.form, "submit", e), "function" == typeof a.form.submit && (a.form.submit = g))
        }, i
    }, ld.prototype = {
        eol: function () {
            return this.pos >= this.string.length
        }, sol: function () {
            return 0 == this.pos
        }, peek: function () {
            return this.string.charAt(this.pos) || void 0
        }, next: function () {
            return this.pos < this.string.length ? this.string.charAt(this.pos++) : void 0
        }, eat: function (a) {
            var b = this.string.charAt(this.pos);
            if ("string" == typeof a)var c = b == a; else var c = b && (a.test ? a.test(b) : a(b));
            return c ? (++this.pos, b) : void 0
        }, eatWhile: function (a) {
            for (var b = this.pos; this.eat(a););
            return this.pos > b
        }, eatSpace: function () {
            for (var a = this.pos; /[\s\u00a0]/.test(this.string.charAt(this.pos));)++this.pos;
            return this.pos > a
        }, skipToEnd: function () {
            this.pos = this.string.length
        }, skipTo: function (a) {
            var b = this.string.indexOf(a, this.pos);
            return b > -1 ? (this.pos = b, !0) : void 0
        }, backUp: function (a) {
            this.pos -= a
        }, column: function () {
            return this.lastColumnPos < this.start && (this.lastColumnValue = Ue(this.string, this.start, this.tabSize, this.lastColumnPos, this.lastColumnValue), this.lastColumnPos = this.start), this.lastColumnValue
        }, indentation: function () {
            return Ue(this.string, null, this.tabSize)
        }, match: function (a, b, c) {
            if ("string" != typeof a) {
                var f = this.string.slice(this.pos).match(a);
                return f && f.index > 0 ? null : (f && b !== !1 && (this.pos += f[0].length), f)
            }
            var d = function (a) {
                return c ? a.toLowerCase() : a
            }, e = this.string.substr(this.pos, a.length);
            return d(e) == d(a) ? (b !== !1 && (this.pos += a.length), !0) : void 0
        }, current: function () {
            return this.string.slice(this.start, this.pos)
        }
    }, w.StringStream = ld, w.TextMarker = md, Qe(md), md.prototype.clear = function () {
        if (!this.explicitlyCleared) {
            var a = this.doc.cm, b = a && !a.curOp;
            if (b && Bb(a), Pe(this, "clear")) {
                var c = this.find();
                c && Me(this, "clear", c.from, c.to)
            }
            for (var d = null, e = null, f = 0; f < this.lines.length; ++f) {
                var g = this.lines[f], h = qd(g.markedSpans, this);
                null != h.to && (e = le(g)), g.markedSpans = rd(g.markedSpans, h), null != h.from ? d = le(g) : this.collapsed && !Cd(this.doc, g) && a && ke(g, yb(a.display))
            }
            if (a && this.collapsed && !a.options.lineWrapping)for (var f = 0; f < this.lines.length; ++f) {
                var i = Bd(a.doc, this.lines[f]), j = G(a.doc, i);
                j > a.display.maxLineLength && (a.display.maxLine = i, a.display.maxLineLength = j, a.display.maxLineChanged = !0)
            }
            null != d && a && Gb(a, d, e + 1), this.lines.length = 0, this.explicitlyCleared = !0, this.atomic && this.doc.cantEdit && (this.doc.cantEdit = !1, a && Ic(a)), b && Cb(a)
        }
    }, md.prototype.find = function () {
        for (var a, b, c = 0; c < this.lines.length; ++c) {
            var d = this.lines[c], e = qd(d.markedSpans, this);
            if (null != e.from || null != e.to) {
                var f = le(d);
                null != e.from && (a = xc(f, e.from)), null != e.to && (b = xc(f, e.to))
            }
        }
        return "bookmark" == this.type ? a : a && {from: a, to: b}
    }, md.prototype.changed = function () {
        var a = this.find(), b = this.doc.cm;
        if (a && b) {
            "bookmark" != this.type && (a = a.from);
            var c = he(this.doc, a.line);
            if (jb(b, c), a.line >= b.display.showingFrom && a.line < b.display.showingTo) {
                for (var d = b.display.lineDiv.firstChild; d; d = d.nextSibling)if (d.lineObj == c) {
                    d.offsetHeight != c.height && ke(c, d.offsetHeight);
                    break
                }
                Fb(b, function () {
                    b.curOp.selectionChanged = b.curOp.forceUpdate = b.curOp.updateMaxLine = !0
                })
            }
        }
    }, md.prototype.attachLine = function (a) {
        if (!this.lines.length && this.doc.cm) {
            var b = this.doc.cm.curOp;
            b.maybeHiddenMarkers && -1 != Ze(b.maybeHiddenMarkers, this) || (b.maybeUnhiddenMarkers || (b.maybeUnhiddenMarkers = [])).push(this)
        }
        this.lines.push(a)
    }, md.prototype.detachLine = function (a) {
        if (this.lines.splice(Ze(this.lines, a), 1), !this.lines.length && this.doc.cm) {
            var b = this.doc.cm.curOp;
            (b.maybeHiddenMarkers || (b.maybeHiddenMarkers = [])).push(this)
        }
    }, w.SharedTextMarker = od, Qe(od), od.prototype.clear = function () {
        if (!this.explicitlyCleared) {
            this.explicitlyCleared = !0;
            for (var a = 0; a < this.markers.length; ++a)this.markers[a].clear();
            Me(this, "clear")
        }
    }, od.prototype.find = function () {
        return this.primary.find()
    };
    var Gd = w.LineWidget = function (a, b, c) {
        if (c)for (var d in c)c.hasOwnProperty(d) && (this[d] = c[d]);
        this.cm = a, this.node = b
    };
    Qe(Gd), Gd.prototype.clear = Hd(function () {
        var a = this.line.widgets, b = le(this.line);
        if (null != b && a) {
            for (var c = 0; c < a.length; ++c)a[c] == this && a.splice(c--, 1);
            a.length || (this.line.widgets = null);
            var d = ne(this.cm, this.line) < this.cm.doc.scrollTop;
            ke(this.line, Math.max(0, this.line.height - Id(this))), d && Pc(this.cm, 0, -this.height), Gb(this.cm, b, b + 1)
        }
    }), Gd.prototype.changed = Hd(function () {
        var a = this.height;
        this.height = null;
        var b = Id(this) - a;
        if (b) {
            ke(this.line, this.line.height + b);
            var c = le(this.line);
            Gb(this.cm, c, c + 1)
        }
    });
    var Kd = w.Line = function (a, b, c) {
        this.text = a, Fd(this, b), this.height = c ? c(this) : 1
    };
    Qe(Kd);
    var Rd = {}, Ud = /[\t\u0000-\u0019\u00ad\u200b\u2028\u2029\uFEFF]/g;
    _d.prototype = {
        chunkSize: function () {
            return this.lines.length
        }, removeInner: function (a, b) {
            for (var c = a, d = a + b; d > c; ++c) {
                var e = this.lines[c];
                this.height -= e.height, Md(e), Me(e, "delete")
            }
            this.lines.splice(a, b)
        }, collapse: function (a) {
            a.splice.apply(a, [a.length, 0].concat(this.lines))
        }, insertInner: function (a, b, c) {
            this.height += c, this.lines = this.lines.slice(0, a).concat(b).concat(this.lines.slice(a));
            for (var d = 0, e = b.length; e > d; ++d)b[d].parent = this
        }, iterN: function (a, b, c) {
            for (var d = a + b; d > a; ++a)if (c(this.lines[a]))return !0
        }
    }, ae.prototype = {
        chunkSize: function () {
            return this.size
        }, removeInner: function (a, b) {
            this.size -= b;
            for (var c = 0; c < this.children.length; ++c) {
                var d = this.children[c], e = d.chunkSize();
                if (e > a) {
                    var f = Math.min(b, e - a), g = d.height;
                    if (d.removeInner(a, f), this.height -= g - d.height, e == f && (this.children.splice(c--, 1), d.parent = null), 0 == (b -= f))break;
                    a = 0
                } else a -= e
            }
            if (this.size - b < 25) {
                var h = [];
                this.collapse(h), this.children = [new _d(h)], this.children[0].parent = this
            }
        }, collapse: function (a) {
            for (var b = 0, c = this.children.length; c > b; ++b)this.children[b].collapse(a)
        }, insertInner: function (a, b, c) {
            this.size += b.length, this.height += c;
            for (var d = 0, e = this.children.length; e > d; ++d) {
                var f = this.children[d], g = f.chunkSize();
                if (g >= a) {
                    if (f.insertInner(a, b, c), f.lines && f.lines.length > 50) {
                        for (; f.lines.length > 50;) {
                            var h = f.lines.splice(f.lines.length - 25, 25), i = new _d(h);
                            f.height -= i.height, this.children.splice(d + 1, 0, i), i.parent = this
                        }
                        this.maybeSpill()
                    }
                    break
                }
                a -= g
            }
        }, maybeSpill: function () {
            if (!(this.children.length <= 10)) {
                var a = this;
                do {
                    var b = a.children.splice(a.children.length - 5, 5), c = new ae(b);
                    if (a.parent) {
                        a.size -= c.size, a.height -= c.height;
                        var e = Ze(a.parent.children, a);
                        a.parent.children.splice(e + 1, 0, c)
                    } else {
                        var d = new ae(a.children);
                        d.parent = a, a.children = [d, c], a = d
                    }
                    c.parent = a.parent
                } while (a.children.length > 10);
                a.parent.maybeSpill()
            }
        }, iterN: function (a, b, c) {
            for (var d = 0, e = this.children.length; e > d; ++d) {
                var f = this.children[d], g = f.chunkSize();
                if (g > a) {
                    var h = Math.min(b, g - a);
                    if (f.iterN(a, h, c))return !0;
                    if (0 == (b -= h))break;
                    a = 0
                } else a -= g
            }
        }
    };
    var be = 0, ce = w.Doc = function (a, b, c) {
        if (!(this instanceof ce))return new ce(a, b, c);
        null == c && (c = 0), ae.call(this, [new _d([new Kd("", null)])]), this.first = c, this.scrollTop = this.scrollLeft = 0, this.cantEdit = !1, this.history = pe(), this.cleanGeneration = 1, this.frontier = c;
        var d = xc(c, 0);
        this.sel = {
            from: d,
            to: d,
            head: d,
            anchor: d,
            shift: !1,
            extend: !1,
            goalColumn: null
        }, this.id = ++be, this.modeOption = b, "string" == typeof a && (a = sf(a)), $d(this, {
            from: d,
            to: d,
            text: a
        }, null, {head: d, anchor: d})
    };
    ce.prototype = $e(ae.prototype, {
        constructor: ce, iter: function (a, b, c) {
            c ? this.iterN(a - this.first, b - a, c) : this.iterN(this.first, this.first + this.size, a)
        }, insert: function (a, b) {
            for (var c = 0, d = 0, e = b.length; e > d; ++d)c += b[d].height;
            this.insertInner(a - this.first, b, c)
        }, remove: function (a, b) {
            this.removeInner(a - this.first, b)
        }, getValue: function (a) {
            var b = je(this, this.first, this.first + this.size);
            return a === !1 ? b : b.join(a || "\n")
        }, setValue: function (a) {
            var b = xc(this.first, 0), c = this.first + this.size - 1;
            qc(this, {from: b, to: xc(c, he(this, c).text.length), text: sf(a), origin: "setValue"}, {
                head: b,
                anchor: b
            }, !0)
        }, replaceRange: function (a, b, c, d) {
            b = Cc(this, b), c = c ? Cc(this, c) : b, wc(this, a, b, c, d)
        }, getRange: function (a, b, c) {
            var d = ie(this, Cc(this, a), Cc(this, b));
            return c === !1 ? d : d.join(c || "\n")
        }, getLine: function (a) {
            var b = this.getLineHandle(a);
            return b && b.text
        }, setLine: function (a, b) {
            Ec(this, a) && wc(this, b, xc(a, 0), Cc(this, xc(a)))
        }, removeLine: function (a) {
            a ? wc(this, "", Cc(this, xc(a - 1)), Cc(this, xc(a))) : wc(this, "", xc(0, 0), Cc(this, xc(1, 0)))
        }, getLineHandle: function (a) {
            return Ec(this, a) ? he(this, a) : void 0
        }, getLineNumber: function (a) {
            return le(a)
        }, getLineHandleVisualStart: function (a) {
            return "number" == typeof a && (a = he(this, a)), Bd(this, a)
        }, lineCount: function () {
            return this.size
        }, firstLine: function () {
            return this.first
        }, lastLine: function () {
            return this.first + this.size - 1
        }, clipPos: function (a) {
            return Cc(this, a)
        }, getCursor: function (a) {
            var c, b = this.sel;
            return c = null == a || "head" == a ? b.head : "anchor" == a ? b.anchor : "end" == a || a === !1 ? b.to : b.from, Ac(c)
        }, somethingSelected: function () {
            return !yc(this.sel.head, this.sel.anchor)
        }, setCursor: Eb(function (a, b, c) {
            var d = Cc(this, "number" == typeof a ? xc(a, b || 0) : a);
            c ? Fc(this, d) : Hc(this, d, d)
        }), setSelection: Eb(function (a, b, c) {
            Hc(this, Cc(this, a), Cc(this, b || a), c)
        }), extendSelection: Eb(function (a, b, c) {
            Fc(this, Cc(this, a), b && Cc(this, b), c)
        }), getSelection: function (a) {
            return this.getRange(this.sel.from, this.sel.to, a)
        }, replaceSelection: function (a, b, c) {
            qc(this, {from: this.sel.from, to: this.sel.to, text: sf(a), origin: c}, b || "around")
        }, undo: Eb(function () {
            sc(this, "undo")
        }), redo: Eb(function () {
            sc(this, "redo")
        }), setExtending: function (a) {
            this.sel.extend = a
        }, historySize: function () {
            var a = this.history;
            return {undo: a.done.length, redo: a.undone.length}
        }, clearHistory: function () {
            this.history = pe(this.history.maxGeneration)
        }, markClean: function () {
            this.cleanGeneration = this.changeGeneration()
        }, changeGeneration: function () {
            return this.history.lastOp = this.history.lastOrigin = null, this.history.generation
        }, isClean: function (a) {
            return this.history.generation == (a || this.cleanGeneration)
        }, getHistory: function () {
            return {done: ve(this.history.done), undone: ve(this.history.undone)}
        }, setHistory: function (a) {
            var b = this.history = pe(this.history.maxGeneration);
            b.done = a.done.slice(0), b.undone = a.undone.slice(0)
        }, markText: function (a, b, c) {
            return nd(this, Cc(this, a), Cc(this, b), c, "range")
        }, setBookmark: function (a, b) {
            var c = {replacedWith: b && (null == b.nodeType ? b.widget : b), insertLeft: b && b.insertLeft};
            return a = Cc(this, a), nd(this, a, a, c, "bookmark")
        }, findMarksAt: function (a) {
            a = Cc(this, a);
            var b = [], c = he(this, a.line).markedSpans;
            if (c)for (var d = 0; d < c.length; ++d) {
                var e = c[d];
                (null == e.from || e.from <= a.ch) && (null == e.to || e.to >= a.ch) && b.push(e.marker.parent || e.marker)
            }
            return b
        }, getAllMarks: function () {
            var a = [];
            return this.iter(function (b) {
                var c = b.markedSpans;
                if (c)for (var d = 0; d < c.length; ++d)null != c[d].from && a.push(c[d].marker)
            }), a
        }, posFromIndex: function (a) {
            var b, c = this.first;
            return this.iter(function (d) {
                var e = d.text.length + 1;
                return e > a ? (b = a, !0) : (a -= e, ++c, void 0)
            }), Cc(this, xc(c, b))
        }, indexFromPos: function (a) {
            a = Cc(this, a);
            var b = a.ch;
            return a.line < this.first || a.ch < 0 ? 0 : (this.iter(this.first, a.line, function (a) {
                b += a.text.length + 1
            }), b)
        }, copy: function (a) {
            var b = new ce(je(this, this.first, this.first + this.size), this.modeOption, this.first);
            return b.scrollTop = this.scrollTop, b.scrollLeft = this.scrollLeft, b.sel = {
                from: this.sel.from,
                to: this.sel.to,
                head: this.sel.head,
                anchor: this.sel.anchor,
                shift: this.sel.shift,
                extend: !1,
                goalColumn: this.sel.goalColumn
            }, a && (b.history.undoDepth = this.history.undoDepth, b.setHistory(this.getHistory())), b
        }, linkedDoc: function (a) {
            a || (a = {});
            var b = this.first, c = this.first + this.size;
            null != a.from && a.from > b && (b = a.from), null != a.to && a.to < c && (c = a.to);
            var d = new ce(je(this, b, c), a.mode || this.modeOption, b);
            return a.sharedHist && (d.history = this.history), (this.linked || (this.linked = [])).push({
                doc: d,
                sharedHist: a.sharedHist
            }), d.linked = [{doc: this, isParent: !0, sharedHist: a.sharedHist}], d
        }, unlinkDoc: function (a) {
            if (a instanceof w && (a = a.doc), this.linked)for (var b = 0; b < this.linked.length; ++b) {
                var c = this.linked[b];
                if (c.doc == a) {
                    this.linked.splice(b, 1), a.unlinkDoc(this);
                    break
                }
            }
            if (a.history == this.history) {
                var d = [a.id];
                fe(a, function (a) {
                    d.push(a.id)
                }, !0), a.history = pe(), a.history.done = ve(this.history.done, d), a.history.undone = ve(this.history.undone, d)
            }
        }, iterLinkedDocs: function (a) {
            fe(this, a)
        }, getMode: function () {
            return this.mode
        }, getEditor: function () {
            return this.cm
        }
    }), ce.prototype.eachLine = ce.prototype.iter;
    var de = "iter insert remove copy getEditor".split(" ");
    for (var ee in ce.prototype)ce.prototype.hasOwnProperty(ee) && Ze(de, ee) < 0 && (w.prototype[ee] = function (a) {
        return function () {
            return a.apply(this.doc, arguments)
        }
    }(ce.prototype[ee]));
    Qe(ce), w.e_stop = Ee, w.e_preventDefault = Be, w.e_stopPropagation = Ce;
    var Ke, Le = 0;
    w.on = He, w.off = Ie, w.signal = Je;
    var Re = 30, Se = w.Pass = {
        toString: function () {
            return "CodeMirror.Pass"
        }
    };
    Te.prototype = {
        set: function (a, b) {
            clearTimeout(this.id), this.id = setTimeout(b, a)
        }
    }, w.countColumn = Ue;
    var Ve = [""], cf = /[\u3040-\u309f\u30a0-\u30ff\u3400-\u4db5\u4e00-\u9fcc\uac00-\ud7af]/, ff = /[\u0300-\u036F\u0483-\u0487\u0488-\u0489\u0591-\u05BD\u05BF\u05C1-\u05C2\u05C4-\u05C5\u05C7\u0610-\u061A\u064B-\u065F\u0670\u06D6-\u06DC\u06DF-\u06E4\u06E7-\u06E8\u06EA-\u06ED\uA66F\uA670-\uA672\uA674-\uA67D\uA69F\udc00-\udfff]/;
    w.replaceGetRect = function (a) {
        lf = a
    };
    var mf = function () {
        if (d)return !1;
        var a = gf("div");
        return "draggable"in a || "dragDrop"in a
    }();
    a ? nf = function (a, b) {
        return 36 == a.charCodeAt(b - 1) && 39 == a.charCodeAt(b)
    } : i && !/Version\/([6-9]|\d\d)\b/.test(navigator.userAgent) ? nf = function (a, b) {
        return /\-[^ \-?]|\?[^ !\'\"\),.\-\/:;\?\]\}]/.test(a.slice(b - 1, b + 1))
    } : e && !/Chrome\/(?:29|[3-9]\d|\d\d\d)\./.test(navigator.userAgent) && (nf = function (a, b) {
        if (b > 1 && 45 == a.charCodeAt(b - 1)) {
            if (/\w/.test(a.charAt(b - 2)) && /[^\-?\.]/.test(a.charAt(b)))return !0;
            if (b > 2 && /[\d\.,]/.test(a.charAt(b - 2)) && /[\d\.,]/.test(a.charAt(b)))return !1
        }
        return /[~!#%&*)=+}\]|\"\.>,:;][({[<]|-[^\-?\.\u2010-\u201f\u2026]|\?[\w~`@#$%\^&*(_=+{[|><]|\u2026[\w~`@#$%\^&*(_=+{[><]/.test(a.slice(b - 1, b + 1))
    });
    var of, qf, sf = 3 != "\n\nb".split(/\n/).length ? function (a) {
        for (var b = 0, c = [], d = a.length; d >= b;) {
            var e = a.indexOf("\n", b);
            -1 == e && (e = a.length);
            var f = a.slice(b, "\r" == a.charAt(e - 1) ? e - 1 : e), g = f.indexOf("\r");
            -1 != g ? (c.push(f.slice(0, g)), b += g + 1) : (c.push(f), b = e + 1)
        }
        return c
    } : function (a) {
        return a.split(/\r\n?|\n/)
    };
    w.splitLines = sf;
    var tf = window.getSelection ? function (a) {
        try {
            return a.selectionStart != a.selectionEnd
        } catch (b) {
            return !1
        }
    } : function (a) {
        try {
            var b = a.ownerDocument.selection.createRange()
        } catch (c) {
        }
        return b && b.parentElement() == a ? 0 != b.compareEndPoints("StartToEnd", b) : !1
    }, uf = function () {
        var a = gf("div");
        return "oncopy"in a ? !0 : (a.setAttribute("oncopy", "return;"), "function" == typeof a.oncopy)
    }(), vf = {
        3: "Enter",
        8: "Backspace",
        9: "Tab",
        13: "Enter",
        16: "Shift",
        17: "Ctrl",
        18: "Alt",
        19: "Pause",
        20: "CapsLock",
        27: "Esc",
        32: "Space",
        33: "PageUp",
        34: "PageDown",
        35: "End",
        36: "Home",
        37: "Left",
        38: "Up",
        39: "Right",
        40: "Down",
        44: "PrintScrn",
        45: "Insert",
        46: "Delete",
        59: ";",
        91: "Mod",
        92: "Mod",
        93: "Mod",
        109: "-",
        107: "=",
        127: "Delete",
        186: ";",
        187: "=",
        188: ",",
        189: "-",
        190: ".",
        191: "/",
        192: "`",
        219: "[",
        220: "\\",
        221: "]",
        222: "'",
        63276: "PageUp",
        63277: "PageDown",
        63275: "End",
        63273: "Home",
        63234: "Left",
        63232: "Up",
        63235: "Right",
        63233: "Down",
        63302: "Insert",
        63272: "Delete"
    };
    w.keyNames = vf, function () {
        for (var a = 0; 10 > a; a++)vf[a + 48] = String(a);
        for (var a = 65; 90 >= a; a++)vf[a] = String.fromCharCode(a);
        for (var a = 1; 12 >= a; a++)vf[a + 111] = vf[a + 63235] = "F" + a
    }();
    var Ef, Jf = function () {
        function c(c) {
            return 255 >= c ? a.charAt(c) : c >= 1424 && 1524 >= c ? "R" : c >= 1536 && 1791 >= c ? b.charAt(c - 1536) : c >= 1792 && 2220 >= c ? "r" : "L"
        }

        var a = "bbbbbbbbbtstwsbbbbbbbbbbbbbbssstwNN%%%NNNNNN,N,N1111111111NNNNNNNLLLLLLLLLLLLLLLLLLLLLLLLLLNNNNNNLLLLLLLLLLLLLLLLLLLLLLLLLLNNNNbbbbbbsbbbbbbbbbbbbbbbbbbbbbbbbbb,N%%%%NNNNLNNNNN%%11NLNNN1LNNNNNLLLLLLLLLLLLLLLLLLLLLLLNLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLLNLLLLLLLL", b = "rrrrrrrrrrrr,rNNmmmmmmrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrmmmmmmmmmmmmmmrrrrrrrnnnnnnnnnn%nnrrrmrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrmmmmmmmmmmmmmmmmmmmNmmmmrrrrrrrrrrrrrrrrrr", d = /[\u0590-\u05f4\u0600-\u06ff\u0700-\u08ac]/, e = /[stwN]/, f = /[LRr]/, g = /[Lb1n]/, h = /[1n]/, i = "L";
        return function (a) {
            if (!d.test(a))return !1;
            for (var l, b = a.length, j = [], k = 0; b > k; ++k)j.push(l = c(a.charCodeAt(k)));
            for (var k = 0, m = i; b > k; ++k) {
                var l = j[k];
                "m" == l ? j[k] = m : m = l
            }
            for (var k = 0, n = i; b > k; ++k) {
                var l = j[k];
                "1" == l && "r" == n ? j[k] = "n" : f.test(l) && (n = l, "r" == l && (j[k] = "R"))
            }
            for (var k = 1, m = j[0]; b - 1 > k; ++k) {
                var l = j[k];
                "+" == l && "1" == m && "1" == j[k + 1] ? j[k] = "1" : "," != l || m != j[k + 1] || "1" != m && "n" != m || (j[k] = m), m = l
            }
            for (var k = 0; b > k; ++k) {
                var l = j[k];
                if ("," == l)j[k] = "N"; else if ("%" == l) {
                    for (var o = k + 1; b > o && "%" == j[o]; ++o);
                    for (var p = k && "!" == j[k - 1] || b - 1 > o && "1" == j[o] ? "1" : "N", q = k; o > q; ++q)j[q] = p;
                    k = o - 1
                }
            }
            for (var k = 0, n = i; b > k; ++k) {
                var l = j[k];
                "L" == n && "1" == l ? j[k] = "L" : f.test(l) && (n = l)
            }
            for (var k = 0; b > k; ++k)if (e.test(j[k])) {
                for (var o = k + 1; b > o && e.test(j[o]); ++o);
                for (var r = "L" == (k ? j[k - 1] : i), s = "L" == (b - 1 > o ? j[o] : i), p = r || s ? "L" : "R", q = k; o > q; ++q)j[q] = p;
                k = o - 1
            }
            for (var u, t = [], k = 0; b > k;)if (g.test(j[k])) {
                var v = k;
                for (++k; b > k && g.test(j[k]); ++k);
                t.push({from: v, to: k, level: 0})
            } else {
                var w = k, x = t.length;
                for (++k; b > k && "L" != j[k]; ++k);
                for (var q = w; k > q;)if (h.test(j[q])) {
                    q > w && t.splice(x, 0, {from: w, to: q, level: 1});
                    var y = q;
                    for (++q; k > q && h.test(j[q]); ++q);
                    t.splice(x, 0, {from: y, to: q, level: 2}), w = q
                } else++q;
                k > w && t.splice(x, 0, {from: w, to: k, level: 1})
            }
            return 1 == t[0].level && (u = a.match(/^\s+/)) && (t[0].from = u[0].length, t.unshift({
                from: 0,
                to: u[0].length,
                level: 0
            })), 1 == Xe(t).level && (u = a.match(/\s+$/)) && (Xe(t).to -= u[0].length, t.push({
                from: b - u[0].length,
                to: b,
                level: 0
            })), t[0].level != Xe(t).level && t.push({from: b, to: b, level: t[0].level}), t
        }
    }();
    return w.version = "3.16.0", w
}(), CodeMirror.defineMode("clike", function (a, b) {
    function n(a, b) {
        var c = a.next();
        if (j[c]) {
            var d = j[c](a, b);
            if (d !== !1)return d
        }
        if ('"' == c || "'" == c)return b.tokenize = o(c), b.tokenize(a, b);
        if (/[\[\]{}\(\),;\:\.]/.test(c))return m = c, null;
        if (/\d/.test(c))return a.eatWhile(/[\w\.]/), "number";
        if ("/" == c) {
            if (a.eat("*"))return b.tokenize = p, p(a, b);
            if (a.eat("/"))return a.skipToEnd(), "comment"
        }
        if (l.test(c))return a.eatWhile(l), "operator";
        a.eatWhile(/[\w\$_]/);
        var e = a.current();
        return f.propertyIsEnumerable(e) ? (h.propertyIsEnumerable(e) && (m = "newstatement"), "keyword") : g.propertyIsEnumerable(e) ? (h.propertyIsEnumerable(e) && (m = "newstatement"), "builtin") : i.propertyIsEnumerable(e) ? "atom" : "variable"
    }

    function o(a) {
        return function (b, c) {
            for (var e, d = !1, f = !1; null != (e = b.next());) {
                if (e == a && !d) {
                    f = !0;
                    break
                }
                d = !d && "\\" == e
            }
            return (f || !d && !k) && (c.tokenize = null), "string"
        }
    }

    function p(a, b) {
        for (var d, c = !1; d = a.next();) {
            if ("/" == d && c) {
                b.tokenize = null;
                break
            }
            c = "*" == d
        }
        return "comment"
    }

    function q(a, b, c, d, e) {
        this.indented = a, this.column = b, this.type = c, this.align = d, this.prev = e
    }

    function r(a, b, c) {
        var d = a.indented;
        return a.context && "statement" == a.context.type && (d = a.context.indented), a.context = new q(d, b, c, null, a.context)
    }

    function s(a) {
        var b = a.context.type;
        return (")" == b || "]" == b || "}" == b) && (a.indented = a.context.indented), a.context = a.context.prev
    }

    var m, c = a.indentUnit, d = b.statementIndentUnit || c, e = b.dontAlignCalls, f = b.keywords || {}, g = b.builtin || {}, h = b.blockKeywords || {}, i = b.atoms || {}, j = b.hooks || {}, k = b.multiLineStrings, l = /[+\-*&%=<>!?|\/]/;
    return {
        startState: function (a) {
            return {tokenize: null, context: new q((a || 0) - c, 0, "top", !1), indented: 0, startOfLine: !0}
        }, token: function (a, b) {
            var c = b.context;
            if (a.sol() && (null == c.align && (c.align = !1), b.indented = a.indentation(), b.startOfLine = !0), a.eatSpace())return null;
            m = null;
            var d = (b.tokenize || n)(a, b);
            if ("comment" == d || "meta" == d)return d;
            if (null == c.align && (c.align = !0), ";" != m && ":" != m && "," != m || "statement" != c.type)if ("{" == m)r(b, a.column(), "}"); else if ("[" == m)r(b, a.column(), "]"); else if ("(" == m)r(b, a.column(), ")"); else if ("}" == m) {
                for (; "statement" == c.type;)c = s(b);
                for ("}" == c.type && (c = s(b)); "statement" == c.type;)c = s(b)
            } else m == c.type ? s(b) : (("}" == c.type || "top" == c.type) && ";" != m || "statement" == c.type && "newstatement" == m) && r(b, a.column(), "statement"); else s(b);
            return b.startOfLine = !1, d
        }, indent: function (a, b) {
            if (a.tokenize != n && null != a.tokenize)return CodeMirror.Pass;
            var f = a.context, g = b && b.charAt(0);
            "statement" == f.type && "}" == g && (f = f.prev);
            var h = g == f.type;
            return "statement" == f.type ? f.indented + ("{" == g ? 0 : d) : !f.align || e && ")" == f.type ? ")" != f.type || h ? f.indented + (h ? 0 : c) : f.indented + d : f.column + (h ? 0 : 1)
        }, electricChars: "{}", blockCommentStart: "/*", blockCommentEnd: "*/", lineComment: "//", fold: "brace"
    }
}), function () {
    function a(a) {
        for (var b = {}, c = a.split(" "), d = 0; d < c.length; ++d)b[c[d]] = !0;
        return b
    }

    function c(a, b) {
        if (!b.startOfLine)return !1;
        for (; ;) {
            if (!a.skipTo("\\")) {
                a.skipToEnd(), b.tokenize = null;
                break
            }
            if (a.next(), a.eol()) {
                b.tokenize = c;
                break
            }
        }
        return "meta"
    }

    function d(a, b) {
        for (var c; null != (c = a.next());)if ('"' == c && !a.eat('"')) {
            b.tokenize = null;
            break
        }
        return "string"
    }

    function e(a, b) {
        for (var c = 0; c < a.length; ++c)CodeMirror.defineMIME(a[c], b)
    }

    var b = "auto if break int case long char register continue return default short do sizeof double static else struct entry switch extern typedef float union for unsigned goto while enum void const signed volatile";
    e(["text/x-csrc", "text/x-c", "text/x-chdr"], {
        name: "clike",
        keywords: a(b),
        blockKeywords: a("case do else for if switch while struct"),
        atoms: a("null"),
        hooks: {"#": c}
    }), e(["text/x-c++src", "text/x-c++hdr"], {
        name: "clike",
        keywords: a(b + " asm dynamic_cast namespace reinterpret_cast try bool explicit new " + "static_cast typeid catch operator template typename class friend private " + "this using const_cast inline public throw virtual delete mutable protected " + "wchar_t"),
        blockKeywords: a("catch class do else finally for if struct switch try while"),
        atoms: a("true false null"),
        hooks: {"#": c}
    }), CodeMirror.defineMIME("text/x-java", {
        name: "clike",
        keywords: a("abstract assert boolean break byte case catch char class const continue default do double else enum extends final finally float for goto if implements import instanceof int interface long native new package private protected public return short static strictfp super switch synchronized this throw throws transient try void volatile while"),
        blockKeywords: a("catch class do else finally for if switch try while"),
        atoms: a("true false null"),
        hooks: {
            "@": function (a) {
                return a.eatWhile(/[\w\$_]/), "meta"
            }
        }
    }), CodeMirror.defineMIME("text/x-csharp", {
        name: "clike",
        keywords: a("abstract as base break case catch checked class const continue default delegate do else enum event explicit extern finally fixed for foreach goto if implicit in interface internal is lock namespace new operator out override params private protected public readonly ref return sealed sizeof stackalloc static struct switch this throw try typeof unchecked unsafe using virtual void volatile while add alias ascending descending dynamic from get global group into join let orderby partial remove select set value var yield"),
        blockKeywords: a("catch class do else finally for foreach if struct switch try while"),
        builtin: a("Boolean Byte Char DateTime DateTimeOffset Decimal Double Guid Int16 Int32 Int64 Object SByte Single String TimeSpan UInt16 UInt32 UInt64 bool byte char decimal double short int long object sbyte float string ushort uint ulong"),
        atoms: a("true false null"),
        hooks: {
            "@": function (a, b) {
                return a.eat('"') ? (b.tokenize = d, d(a, b)) : (a.eatWhile(/[\w\$_]/), "meta")
            }
        }
    }), CodeMirror.defineMIME("text/x-scala", {
        name: "clike",
        keywords: a("abstract case catch class def do else extends false final finally for forSome if implicit import lazy match new null object override package private protected return sealed super this throw trait try trye type val var while with yield _ : = => <- <: <% >: # @ assert assume require print println printf readLine readBoolean readByte readShort readChar readInt readLong readFloat readDouble AnyVal App Application Array BufferedIterator BigDecimal BigInt Char Console Either Enumeration Equiv Error Exception Fractional Function IndexedSeq Integral Iterable Iterator List Map Numeric Nil NotNull Option Ordered Ordering PartialFunction PartialOrdering Product Proxy Range Responder Seq Serializable Set Specializable Stream StringBuilder StringContext Symbol Throwable Traversable TraversableOnce Tuple Unit Vector :: #:: Boolean Byte Character CharSequence Class ClassLoader Cloneable Comparable Compiler Double Exception Float Integer Long Math Number Object Package Pair Process Runtime Runnable SecurityManager Short StackTraceElement StrictMath String StringBuffer System Thread ThreadGroup ThreadLocal Throwable Triple Void"),
        blockKeywords: a("catch class do else finally for forSome if match switch try while"),
        atoms: a("true false null"),
        hooks: {
            "@": function (a) {
                return a.eatWhile(/[\w\$_]/), "meta"
            }
        }
    }), e(["x-shader/x-vertex", "x-shader/x-fragment"], {
        name: "clike",
        keywords: a("float int bool void vec2 vec3 vec4 ivec2 ivec3 ivec4 bvec2 bvec3 bvec4 mat2 mat3 mat4 sampler1D sampler2D sampler3D samplerCube sampler1DShadow sampler2DShadowconst attribute uniform varying break continue discard return for while do if else struct in out inout"),
        blockKeywords: a("for while do if else struct"),
        builtin: a("radians degrees sin cos tan asin acos atan pow exp log exp2 sqrt inversesqrt abs sign floor ceil fract mod min max clamp mix step smootstep length distance dot cross normalize ftransform faceforward reflect refract matrixCompMult lessThan lessThanEqual greaterThan greaterThanEqual equal notEqual any all not texture1D texture1DProj texture1DLod texture1DProjLod texture2D texture2DProj texture2DLod texture2DProjLod texture3D texture3DProj texture3DLod texture3DProjLod textureCube textureCubeLod shadow1D shadow2D shadow1DProj shadow2DProj shadow1DLod shadow2DLod shadow1DProjLod shadow2DProjLod dFdx dFdy fwidth noise1 noise2 noise3 noise4"),
        atoms: a("true false gl_FragColor gl_SecondaryColor gl_Normal gl_Vertex gl_MultiTexCoord0 gl_MultiTexCoord1 gl_MultiTexCoord2 gl_MultiTexCoord3 gl_MultiTexCoord4 gl_MultiTexCoord5 gl_MultiTexCoord6 gl_MultiTexCoord7 gl_FogCoord gl_Position gl_PointSize gl_ClipVertex gl_FrontColor gl_BackColor gl_FrontSecondaryColor gl_BackSecondaryColor gl_TexCoord gl_FogFragCoord gl_FragCoord gl_FrontFacing gl_FragColor gl_FragData gl_FragDepth gl_ModelViewMatrix gl_ProjectionMatrix gl_ModelViewProjectionMatrix gl_TextureMatrix gl_NormalMatrix gl_ModelViewMatrixInverse gl_ProjectionMatrixInverse gl_ModelViewProjectionMatrixInverse gl_TexureMatrixTranspose gl_ModelViewMatrixInverseTranspose gl_ProjectionMatrixInverseTranspose gl_ModelViewProjectionMatrixInverseTranspose gl_TextureMatrixInverseTranspose gl_NormalScale gl_DepthRange gl_ClipPlane gl_Point gl_FrontMaterial gl_BackMaterial gl_LightSource gl_LightModel gl_FrontLightModelProduct gl_BackLightModelProduct gl_TextureColor gl_EyePlaneS gl_EyePlaneT gl_EyePlaneR gl_EyePlaneQ gl_FogParameters gl_MaxLights gl_MaxClipPlanes gl_MaxTextureUnits gl_MaxTextureCoords gl_MaxVertexAttribs gl_MaxVertexUniformComponents gl_MaxVaryingFloats gl_MaxVertexTextureImageUnits gl_MaxTextureImageUnits gl_MaxFragmentUniformComponents gl_MaxCombineTextureImageUnits gl_MaxDrawBuffers"),
        hooks: {"#": c}
    })
}(), CodeMirror.defineMode("htmlmixed", function (a, b) {
    function i(a, b) {
        var f = b.htmlState.tagName, g = c.token(a, b.htmlState);
        if ("script" == f && /\btag\b/.test(g) && ">" == a.current()) {
            var h = a.string.slice(Math.max(0, a.pos - 100), a.pos).match(/\btype\s*=\s*("[^"]+"|'[^']+'|\S+)[^<]*$/i);
            h = h ? h[1] : "", h && /[\"\']/.test(h.charAt(0)) && (h = h.slice(1, h.length - 1));
            for (var i = 0; i < e.length; ++i) {
                var j = e[i];
                if ("string" == typeof j.matches ? h == j.matches : j.matches.test(h)) {
                    j.mode && (b.token = k, b.localMode = j.mode, b.localState = j.mode.startState && j.mode.startState(c.indent(b.htmlState, "")));
                    break
                }
            }
        } else"style" == f && /\btag\b/.test(g) && ">" == a.current() && (b.token = l, b.localMode = d, b.localState = d.startState(c.indent(b.htmlState, "")));
        return g
    }

    function j(a, b, c) {
        var f, d = a.current(), e = d.search(b);
        return e > -1 ? a.backUp(d.length - e) : (f = d.match(/<\/?$/)) && (a.backUp(d.length), a.match(b, !1) || a.match(d[0])), c
    }

    function k(a, b) {
        return a.match(/^<\/\s*script\s*>/i, !1) ? (b.token = i, b.localState = b.localMode = null, i(a, b)) : j(a, /<\/\s*script\s*>/, b.localMode.token(a, b.localState))
    }

    function l(a, b) {
        return a.match(/^<\/\s*style\s*>/i, !1) ? (b.token = i, b.localState = b.localMode = null, i(a, b)) : j(a, /<\/\s*style\s*>/, d.token(a, b.localState))
    }

    var c = CodeMirror.getMode(a, {
        name: "xml",
        htmlMode: !0
    }), d = CodeMirror.getMode(a, "css"), e = [], f = b && b.scriptTypes;
    if (e.push({
            matches: /^(?:text|application)\/(?:x-)?(?:java|ecma)script$|^$/i,
            mode: CodeMirror.getMode(a, "javascript")
        }), f)for (var g = 0; g < f.length; ++g) {
        var h = f[g];
        e.push({matches: h.matches, mode: h.mode && CodeMirror.getMode(a, h.mode)})
    }
    return e.push({matches: /./, mode: CodeMirror.getMode(a, "text/plain")}), {
        startState: function () {
            var a = c.startState();
            return {token: i, localMode: null, localState: null, htmlState: a}
        }, copyState: function (a) {
            if (a.localState)var b = CodeMirror.copyState(a.localMode, a.localState);
            return {
                token: a.token,
                localMode: a.localMode,
                localState: b,
                htmlState: CodeMirror.copyState(c, a.htmlState)
            }
        }, token: function (a, b) {
            return b.token(a, b)
        }, indent: function (a, b) {
            return !a.localMode || /^\s*<\//.test(b) ? c.indent(a.htmlState, b) : a.localMode.indent ? a.localMode.indent(a.localState, b) : CodeMirror.Pass
        }, electricChars: "/{}:", innerMode: function (a) {
            return {state: a.localState || a.htmlState, mode: a.localMode || c}
        }
    }
}, "xml", "javascript", "css"), CodeMirror.defineMIME("text/html", "htmlmixed"), function () {
    function a(a) {
        for (var b = {}, c = a.split(" "), d = 0; d < c.length; ++d)b[c[d]] = !0;
        return b
    }

    function b(a) {
        return function (b, c) {
            return b.match(a) ? c.tokenize = null : b.skipToEnd(), "string"
        }
    }

    var c = {
        name: "clike",
        keywords: a("abstract and array as break case catch class clone const continue declare default do else elseif enddeclare endfor endforeach endif endswitch endwhile extends final for foreach function global goto if implements interface instanceof namespace new or private protected public static switch throw trait try use var while xor die echo empty exit eval include include_once isset list require require_once return print unset __halt_compiler self static parent"),
        blockKeywords: a("catch do else elseif for foreach if switch try while"),
        atoms: a("true false null TRUE FALSE NULL __CLASS__ __DIR__ __FILE__ __LINE__ __METHOD__ __FUNCTION__ __NAMESPACE__"),
        builtin: a("func_num_args func_get_arg func_get_args strlen strcmp strncmp strcasecmp strncasecmp each error_reporting define defined trigger_error user_error set_error_handler restore_error_handler get_declared_classes get_loaded_extensions extension_loaded get_extension_funcs debug_backtrace constant bin2hex sleep usleep time mktime gmmktime strftime gmstrftime strtotime date gmdate getdate localtime checkdate flush wordwrap htmlspecialchars htmlentities html_entity_decode md5 md5_file crc32 getimagesize image_type_to_mime_type phpinfo phpversion phpcredits strnatcmp strnatcasecmp substr_count strspn strcspn strtok strtoupper strtolower strpos strrpos strrev hebrev hebrevc nl2br basename dirname pathinfo stripslashes stripcslashes strstr stristr strrchr str_shuffle str_word_count strcoll substr substr_replace quotemeta ucfirst ucwords strtr addslashes addcslashes rtrim str_replace str_repeat count_chars chunk_split trim ltrim strip_tags similar_text explode implode setlocale localeconv parse_str str_pad chop strchr sprintf printf vprintf vsprintf sscanf fscanf parse_url urlencode urldecode rawurlencode rawurldecode readlink linkinfo link unlink exec system escapeshellcmd escapeshellarg passthru shell_exec proc_open proc_close rand srand getrandmax mt_rand mt_srand mt_getrandmax base64_decode base64_encode abs ceil floor round is_finite is_nan is_infinite bindec hexdec octdec decbin decoct dechex base_convert number_format fmod ip2long long2ip getenv putenv getopt microtime gettimeofday getrusage uniqid quoted_printable_decode set_time_limit get_cfg_var magic_quotes_runtime set_magic_quotes_runtime get_magic_quotes_gpc get_magic_quotes_runtime import_request_variables error_log serialize unserialize memory_get_usage var_dump var_export debug_zval_dump print_r highlight_file show_source highlight_string ini_get ini_get_all ini_set ini_alter ini_restore get_include_path set_include_path restore_include_path setcookie header headers_sent connection_aborted connection_status ignore_user_abort parse_ini_file is_uploaded_file move_uploaded_file intval floatval doubleval strval gettype settype is_null is_resource is_bool is_long is_float is_int is_integer is_double is_real is_numeric is_string is_array is_object is_scalar ereg ereg_replace eregi eregi_replace split spliti join sql_regcase dl pclose popen readfile rewind rmdir umask fclose feof fgetc fgets fgetss fread fopen fpassthru ftruncate fstat fseek ftell fflush fwrite fputs mkdir rename copy tempnam tmpfile file file_get_contents stream_select stream_context_create stream_context_set_params stream_context_set_option stream_context_get_options stream_filter_prepend stream_filter_append fgetcsv flock get_meta_tags stream_set_write_buffer set_file_buffer set_socket_blocking stream_set_blocking socket_set_blocking stream_get_meta_data stream_register_wrapper stream_wrapper_register stream_set_timeout socket_set_timeout socket_get_status realpath fnmatch fsockopen pfsockopen pack unpack get_browser crypt opendir closedir chdir getcwd rewinddir readdir dir glob fileatime filectime filegroup fileinode filemtime fileowner fileperms filesize filetype file_exists is_writable is_writeable is_readable is_executable is_file is_dir is_link stat lstat chown touch clearstatcache mail ob_start ob_flush ob_clean ob_end_flush ob_end_clean ob_get_flush ob_get_clean ob_get_length ob_get_level ob_get_status ob_get_contents ob_implicit_flush ob_list_handlers ksort krsort natsort natcasesort asort arsort sort rsort usort uasort uksort shuffle array_walk count end prev next reset current key min max in_array array_search extract compact array_fill range array_multisort array_push array_pop array_shift array_unshift array_splice array_slice array_merge array_merge_recursive array_keys array_values array_count_values array_reverse array_reduce array_pad array_flip array_change_key_case array_rand array_unique array_intersect array_intersect_assoc array_diff array_diff_assoc array_sum array_filter array_map array_chunk array_key_exists pos sizeof key_exists assert assert_options version_compare ftok str_rot13 aggregate session_name session_module_name session_save_path session_id session_regenerate_id session_decode session_register session_unregister session_is_registered session_encode session_start session_destroy session_unset session_set_save_handler session_cache_limiter session_cache_expire session_set_cookie_params session_get_cookie_params session_write_close preg_match preg_match_all preg_replace preg_replace_callback preg_split preg_quote preg_grep overload ctype_alnum ctype_alpha ctype_cntrl ctype_digit ctype_lower ctype_graph ctype_print ctype_punct ctype_space ctype_upper ctype_xdigit virtual apache_request_headers apache_note apache_lookup_uri apache_child_terminate apache_setenv apache_response_headers apache_get_version getallheaders mysql_connect mysql_pconnect mysql_close mysql_select_db mysql_create_db mysql_drop_db mysql_query mysql_unbuffered_query mysql_db_query mysql_list_dbs mysql_list_tables mysql_list_fields mysql_list_processes mysql_error mysql_errno mysql_affected_rows mysql_insert_id mysql_result mysql_num_rows mysql_num_fields mysql_fetch_row mysql_fetch_array mysql_fetch_assoc mysql_fetch_object mysql_data_seek mysql_fetch_lengths mysql_fetch_field mysql_field_seek mysql_free_result mysql_field_name mysql_field_table mysql_field_len mysql_field_type mysql_field_flags mysql_escape_string mysql_real_escape_string mysql_stat mysql_thread_id mysql_client_encoding mysql_get_client_info mysql_get_host_info mysql_get_proto_info mysql_get_server_info mysql_info mysql mysql_fieldname mysql_fieldtable mysql_fieldlen mysql_fieldtype mysql_fieldflags mysql_selectdb mysql_createdb mysql_dropdb mysql_freeresult mysql_numfields mysql_numrows mysql_listdbs mysql_listtables mysql_listfields mysql_db_name mysql_dbname mysql_tablename mysql_table_name pg_connect pg_pconnect pg_close pg_connection_status pg_connection_busy pg_connection_reset pg_host pg_dbname pg_port pg_tty pg_options pg_ping pg_query pg_send_query pg_cancel_query pg_fetch_result pg_fetch_row pg_fetch_assoc pg_fetch_array pg_fetch_object pg_fetch_all pg_affected_rows pg_get_result pg_result_seek pg_result_status pg_free_result pg_last_oid pg_num_rows pg_num_fields pg_field_name pg_field_num pg_field_size pg_field_type pg_field_prtlen pg_field_is_null pg_get_notify pg_get_pid pg_result_error pg_last_error pg_last_notice pg_put_line pg_end_copy pg_copy_to pg_copy_from pg_trace pg_untrace pg_lo_create pg_lo_unlink pg_lo_open pg_lo_close pg_lo_read pg_lo_write pg_lo_read_all pg_lo_import pg_lo_export pg_lo_seek pg_lo_tell pg_escape_string pg_escape_bytea pg_unescape_bytea pg_client_encoding pg_set_client_encoding pg_meta_data pg_convert pg_insert pg_update pg_delete pg_select pg_exec pg_getlastoid pg_cmdtuples pg_errormessage pg_numrows pg_numfields pg_fieldname pg_fieldsize pg_fieldtype pg_fieldnum pg_fieldprtlen pg_fieldisnull pg_freeresult pg_result pg_loreadall pg_locreate pg_lounlink pg_loopen pg_loclose pg_loread pg_lowrite pg_loimport pg_loexport echo print global static exit array empty eval isset unset die include require include_once require_once"),
        multiLineStrings: !0,
        hooks: {
            $: function (a) {
                return a.eatWhile(/[\w\$_]/), "variable-2"
            }, "<": function (a, c) {
                return a.match(/<</) ? (a.eatWhile(/[\w\.]/), c.tokenize = b(a.current().slice(3)), c.tokenize(a, c)) : !1
            }, "#": function (a) {
                for (; !a.eol() && !a.match("?>", !1);)a.next();
                return "comment"
            }, "/": function (a) {
                if (a.eat("/")) {
                    for (; !a.eol() && !a.match("?>", !1);)a.next();
                    return "comment"
                }
                return !1
            }
        }
    };
    CodeMirror.defineMode("php", function (a, b) {
        function f(a, b) {
            var c = b.curMode == e;
            if (a.sol() && '"' != b.pending && (b.pending = null), c)return c && null == b.php.tokenize && a.match("?>") ? (b.curMode = d, b.curState = b.html, "meta") : e.token(a, b.curState);
            if (a.match(/^<\?\w*/))return b.curMode = e, b.curState = b.php, "meta";
            if ('"' == b.pending) {
                for (; !a.eol() && '"' != a.next(););
                var f = "string"
            } else if (b.pending && a.pos < b.pending.end) {
                a.pos = b.pending.end;
                var f = b.pending.style
            } else var f = d.token(a, b.curState);
            b.pending = null;
            var g = a.current(), h = g.search(/<\?/);
            return -1 != h && (b.pending = "string" == f && /\"$/.test(g) && !/\?>/.test(g) ? '"' : {
                end: a.pos,
                style: f
            }, a.backUp(g.length - h)), f
        }

        var d = CodeMirror.getMode(a, "text/html"), e = CodeMirror.getMode(a, c);
        return {
            startState: function () {
                var a = CodeMirror.startState(d), c = CodeMirror.startState(e);
                return {html: a, php: c, curMode: b.startOpen ? e : d, curState: b.startOpen ? c : a, pending: null}
            },
            copyState: function (a) {
                var h, b = a.html, c = CodeMirror.copyState(d, b), f = a.php, g = CodeMirror.copyState(e, f);
                return h = a.curMode == d ? c : g, {
                    html: c,
                    php: g,
                    curMode: a.curMode,
                    curState: h,
                    pending: a.pending
                }
            },
            token: f,
            indent: function (a, b) {
                return a.curMode != e && /^\s*<\//.test(b) || a.curMode == e && /^\?>/.test(b) ? d.indent(a.html, b) : a.curMode.indent(a.curState, b)
            },
            electricChars: "/{}:",
            blockCommentStart: "/*",
            blockCommentEnd: "*/",
            lineComment: "//",
            innerMode: function (a) {
                return {state: a.curState, mode: a.curMode}
            }
        }
    }, "htmlmixed", "clike"), CodeMirror.defineMIME("application/x-httpd-php", "php"), CodeMirror.defineMIME("application/x-httpd-php-open", {
        name: "php",
        startOpen: !0
    }), CodeMirror.defineMIME("text/x-php", c)
}();
/*global jQuery: false, CodeMirror: false, tmpl: false, jwerty: false */
'use strict';

/**
 * Format byte size into human readable form.
 *
 * @param {Integer} size
 *
 * @return {String}
 */
function niceBytesize(size) {
    var i = Math.max(Math.floor(Math.log(0 | size) / Math.log(1024)), 0);
    return Math.round(size / Math.pow(1024, i), 2).toFixed(0) + ' ' + niceBytesize.units[i];
}
niceBytesize.units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];

// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function () {
    var cache = {};

    window.tmpl = function tmpl(str, data) {
        data = typeof data === 'string' ? {data: data} : data;

        // Figure out if we're getting a template, or if we need to
        // load the template - and be sure to cache the result.
        var fn = !/\W/.test(str) ? cache[str] = cache[str] || tmpl(document.getElementById('template_' + str).innerHTML) :

            // Generate a reusable function that will serve as a template
            // generator (and which will be cached).
            /*jshint evil:true */
            new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};" +

                // Introduce the data as local variables using with(){}
            "with(obj){p.push('" +

                // Convert the template into pure JavaScript
            str
                .replace(/[\r\t\n]/g, " ")
                .split("{{").join("\t")
                .replace(/((^|\}\})[^\t]*)'/g, "$1\r")
                .replace(/\t=(.*?)\}\}/g, "',$1,'")
                .split("\t").join("');")
                .split("}}").join("p.push('")
                .split("\r").join("\\'") +
            "');}return p.join('');");

        // Provide some basic currying to the user
        return data ? fn(data) : fn;
    };
})();

// Application
jQuery(function ($) {
    var $console = $('#console');
    var $response = $('#response');
    var $controlbar = $('#controlbar');
    var $editor = $('#editor');
    var action = $console.data('action');
    var $execute = $('#execute');
    var $controls = $('#controls');
    var is_focused = 0;
    var tabs = {
        active: 'output',
        initial: 'output',
        activeClass: 'active'
    };
    var editor = new CodeMirror($editor[0], {
        mode: 'text/x-php',
        theme: 'laravel',
        lineNumbers: true,
        indentUnit: 4,
        tabSize: 4,
        indentWithTabs: true,
        autofocus: true
    });

    editor.on('focus', function () {
        is_focused = 1;
    });

    editor.on('blur', function () {
        is_focused = 0;
    });

    editor.on('change', function () {
        if (Modernizr.localstorage && localStorage.remember / 1 === 1) {
            localStorage.code = editor.getValue();
        }
    });

    editor.on('cursorActivity', function () {
        if (Modernizr.localstorage && localStorage.remember / 1 === 1 && is_focused) {
            var cursor = editor.getCursor();
            localStorage.line = cursor.line;
            localStorage.char = cursor.ch;
        }
    });

    /**
     * Send a code to be executed, and handle the response.
     *
     * @return {Void}
     */
    function execute() {
        var execution = $.ajax(action, {
            type: 'POST',
            cache: false,
            data: {
                code: editor.getValue()
            },
            dataType: 'text',
            timeout: 30000
        });

        $controls.html(tmpl('controls_loading'));
        execution.then(responseDone, responseFail);
    }

    /**
     * Execution response success handler.
     *
     * @param  {String} res
     *
     * @return {Void}
     */
    function responseDone(res) {
        console.log('response:', res);
        try {
            res = JSON.parse(res);
        } catch (e) {
        }
        $response.html(tmpl('output', res)).show();
        $controls.html(tmpl(typeof res === 'object' ? 'controls' : 'ended_unexpectedly', res));
        activate(!tabs.active || typeof res === 'string' || res.error ? tabs.initial : tabs.active);
        $response.imagesLoaded(resize);
    }

    /**
     * Execution response failure handler.
     *
     * @param  {Object} res
     *
     * @return {Void}
     */
    function responseFail(res) {
        console.log('fail:', res);
        var json;
        try {
            json = JSON.parse(res.responseText);
        } catch (e) {
        }
        $response.html(tmpl('output', json || res.responseText)).show();
        $controls.html(tmpl('controls_error', res));
        activate(!tabs.active || typeof res === 'string' || res.error ? tabs.initial : tabs.active);
        $response.imagesLoaded(resize);
    }

    /**
     * Activate a tab.
     *
     * @param  {String}  tab
     * @param  {Boolean} force
     *
     * @return {Void}
     */
    function activate(tab) {
        var $holders = $response.children();
        var $buttons = $controls.find('[data-show]');

        $buttons.removeClass(tabs.activeClass).filter('[data-show=' + tab + ']').addClass(tabs.activeClass);
        $holders.hide().filter('[data-tab=' + tab + ']').show();
        tabs.active = tab;
        resize();
    }

    /**
     * Reset code view.
     *
     * @return {Void}
     */
    function reset() {
        tabs.active = null;
        $response.hide().html('');
        $controls.html(tmpl('controls_intro', {checked: Modernizr.localstorage && localStorage.remember / 1 === 1}));
        resize();
    }

    /**
     * Resize sections.
     *
     * @return {Void}
     */
    function resize() {
        var output_height = $response.is(':visible') ? $response.outerHeight() : 0;
        var newHeight = Math.round($console.outerHeight() - output_height - $controlbar.outerHeight());
        editor.setSize(null, newHeight);
        $editor.height(newHeight);
    }

    // Execution initiators
    $execute.on('click', function () {
        execute();
    });

    jwerty.key('ctrl+enter/cmd+enter', function () {
        execute();
    });

    // Toggle profiler tabs
    $controls.on('click', '[data-show]', function () {
        activate($(this).data('show'));
    });

    // Reset view
    jwerty.key('esc', function () {
        reset();
        editor.focus();
    });

    // Refocus editor on pressing TAB
    jwerty.key('tab', function () {
        if (!is_focused) {
            editor.focus();
            return false;
        }
    });

    // Resize window
    $(window).on('resize', function () {
        resize();
    });

    // Remember code
    if (Modernizr.localstorage) {
        var checked_class = 'checked';

        // Restore the last editor state
        if (Modernizr.localstorage && localStorage.remember / 1 === 1) {
            editor.setValue(localStorage.code);
            editor.setCursor(localStorage.line ? localStorage.line / 1 : 0, localStorage.char ? localStorage.char / 1 : 0);
        }

        $controls.on('click', '.remember .button', function () {
            var do_remember = localStorage.remember / 1 !== 1;
            var cursor = editor.getCursor();

            $(this)[do_remember ? 'addClass' : 'removeClass'](checked_class);

            if (do_remember) {
                localStorage.remember = 1;
                localStorage.code = editor.getValue();
                localStorage.line = cursor.line;
                localStorage.char = cursor.ch;
            } else {
                localStorage.clear();
            }

        })[localStorage.remember / 1 === 1 ? 'addClass' : 'removeClass'](checked_class);
    }

    // Initiate view reset
    reset();
});
