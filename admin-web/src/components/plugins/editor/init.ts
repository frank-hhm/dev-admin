import { Boot, IDomEditor } from '@wangeditor/editor'

import mateSelectModal from "@/components/mate/select-modal/index.vue";

import {
    getCurrentInstance,
    h
} from "vue";


class Tool {
    active:boolean | undefined;
    constructor(
        public readonly callback: Function, 
        public readonly key: string, 
        public readonly title: string, 
        public readonly tag: string,
    ) { }
    getValue(editor: IDomEditor): string | boolean {
        return editor.getHtml();
    }
    isActive() {
        return this.active || false;
    }
    isDisabled() {
        return false;
    }
    exec(editor: IDomEditor) {
        this.active = !this.active;
        editor.emit('clickSource', this.active);

        const callback = editor.getMenuConfig(this.key).callback || '';
        if (callback && typeof callback === 'function') {
            callback(this.active, editor);
        }
    }
}

const createTool = (callback: Function, key: string, title: string, tag: string): any => {
    return new Tool(callback, key, title, tag);
};

const tools = [{
    key: "imageTool",
    title: "图片",
    tag: "button",
    active:false,
    callback: () => {

    }
}, {
    key: "videoTool",
    title: "视频",
    tag: "button",
    active:false,
    callback: () => {

    }
}]

export const initEditorTool = () => {
    for (let i = 0; i < tools.length; i++) {
        const tool = tools[i];
        const toolConf = {
            key: tool.key,
            factory() {
                return createTool(tool.callback, tool.key, tool.title, tool.tag);
            },
        };
        Boot.registerMenu(toolConf);
    }
};