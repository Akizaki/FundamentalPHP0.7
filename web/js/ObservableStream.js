
"use strict";
/**
 * 
 * @type type
 */
const ObservableStream = {};
{
    /**
     * 
     * @type type
     */
    const
            STREAM_PIPE = Symbol('STREAM_PIPE'),
            STREAM_DATA_LISTENER = Symbol('STREAM_DATA_LISTENER'),
            STREAM_END_LISTENER = Symbol('STREAM_END_LISTENER'),
            STREAM_VALUE = Symbol('STREAM_VALUE'),
            STREAM_EOF = Symbol('STREAM_EOF'),
            STREAM_FLOWING = Symbol('STREAM_FLOWING');
    /**
     * 
     * @param {type} handler
     * @returns {Function}
     */
    const handling_data_through = handler => data => typeof handler === "function" ? handler(data) : data;
    /**
     * 
     * @type type
     */
    const data_through_handler = handling_data_through(data => data);
    /**
     * 
     * @param {type} o
     * @returns {Boolean}
     */
    const has_subscribe_api = o => o && typeof o.subscribe === "function";
    /**
     * TODO::
     * @type type
     */
    const enqueue = (function (component) {}).bind([]);
    /**
     * 
     * @param {type} callback
     * @param {type} new_values
     * @param {type} prev_values
     */
    const diff_each = (callback, new_values, prev_values) => {
        const a = Object(new_values), b = Object(prev_values);
        return Object.keys(a).concat(b).filter(key => a[key] !== b[key])
                .forEach(key => callback(key, a[key], b[key]));
    };
    /**
     * 
     * @type type
     */
    const ReadableStream = {

        /**
         * 
         * @param {type} data
         * @returns {ObservableStream}
         */
        push(data) {
            if (!this[STREAM_FLOWING]) {
                throw new Error("STREAM WAS ALREADY CLOSED: you can not use pipe");
            }
            const flowing = data !== STREAM_EOF;
            if (Array.isArray(data)) {
                this[STREAM_VALUE] = !Array.isArray(this[STREAM_VALUE]) ?
                        [].concat(data.filter(data_through_handler)) :
                        this[STREAM_VALUE].concat(data.filter(data_through_handler));
            } else {
                this[STREAM_VALUE] = !Array.isArray(this[STREAM_VALUE]) ?
                        [].concat([data].filter(data_through_handler)) :
                        this[STREAM_VALUE].concat([data].filter(data_through_handler));
            }
            return has_subscribe_api(ObservableStream) &&
                    ObservableStream.subscribe(data_through_handler, this[STREAM_VALUE]);
        },
        /**
         * 
         * @param {type} handler
         * @returns {ReadableStream|Object}
         */
        pipe(handler) {
            if (!this[STREAM_FLOWING]) {
                throw new Error("STREAM WAS ALREADY CLOSED: you can not use pipe");
            }
            return  has_subscribe_api(handler) ?
                    handler : ObservableStream.subscribe(handler, this[STREAM_VALUE]);
        }
    };
    /**
     * 
     * @param {Function} handler
     * @param {} init
     * @returns {ReadableStream}
     */
    ObservableStream.subscribe = (handler, init = undefined) =>
        typeof handler !== "function"
                ? ObservableStream.subscribe(data_through_handler, handler)
                : Object.create(ReadableStream, {
                    [STREAM_DATA_LISTENER]: {value: handler},
                    [STREAM_END_LISTENER]: {value: null, writable: true},
                    [STREAM_PIPE]: {value: null, writable: true},
                    [STREAM_FLOWING]: {value: true, writable: true},
                    [STREAM_VALUE]: {value: init === undefined ? init : handler(init), writable: true}
                });
    /**
     * 
     * @type type
     */
    const DOM = {

        /**
         * 
         * @param {type} data
         * @returns {Text|Object|HTMLElement|DOM@call;fragments}
         */
        build(data) {
            return Array.isArray(data) ? this.fragments(data)
                    : data === Object(data) ? this.elements(data)
                    : document.createTextNode(data);
        },
        /**
         * 
         * @param {type} source
         * @returns {HTMLElement}
         */
        elements(source) {
            return Object.keys(source).filter(key => key !== "$").reduce((element, key) => {
                element = element !== null ? element : document.createElement(element);
                source[key] === Object(source[key]) ? this.childElements(element, source[key]) :
                        document.createTextNode(source[key]);
                return element;
            }, null);
        },
        /**
         * 
         * @param {type} parent
         * @param {type} source
         * @returns {Object}
         */
        childElements: (parent, source) => Object.keys(source).reduce((element, key) => {
                element.appendChild(document.createElement(key))
                        .appendChild(document.createTextNode(source[key]));
                return element;
            }, parent),
        /**
         * 
         * @param {Array} source_list
         * @returns {createDocumentFragment}
         */
        fragments(source_list) {
            return source_list.reduce((fragment, source) => {
                fragment.appendChild(this.elements(source));
                return fragment;
            }, document.createDocumentFragment());
        }
    };
    const Http = {

        /**
         * 
         * @param {type} uri
         * @param {type} header
         * @returns {Promise}
         */
        $get: (uri, header) => window.fetch ? fetch(uri, {headers: new Headers(header)}) :
                    new Promise(resolve => {
                        const xhr = new XMLHttpRequest();
                        xhr.addEventListener("loadend", resolve, false);
                        xhr.open("get", uri, true);
                        Object.keys(header).forEach(key => xhr.setRequestHeader(key, header[key]));
                        xhr.send("");
                    }),

        /**
         * 
         * @param {type} uri
         * @param {type} params
         * @returns {Promise}
         */
        $request: (uri, params) => window.fetch ? fetch(uri, params) :
                    new Promise(resolve => {
                        const xhr = new XMLHttpRequest();
                        xhr.addEventListener("loadend", resolve, false);
                        xhr.open(params.method, uri, true);
                        Object.keys(params.header).forEach(key => xhr.setRequestHeader(key, params.header[key]));
                        xhr.send(params.body);
                    }),

        /**
         * 
         * @param {type} r
         * @returns {Array|Object}
         */
        toJson: r => typeof r.json === "function" ? r.json() : JSON.parse(r.currentTarget.response),
    };
    /**
     * 
     * @type type
     */
    ObservableStream.build = DOM.build.bind(DOM);
    ObservableStream.$get = Http.$get.bind(Http);
    ObservableStream.$request = Http.$request.bind(Http);
    ObservableStream.toJson = Http.toJson.bind(Http);
    ObservableStream.push = ReadableStream.push.bind(ReadableStream);
    ObservableStream.pipe = ReadableStream.pipe.bind(ReadableStream);
}