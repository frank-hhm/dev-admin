import { ref } from "vue"
import store, { useAdminStoreHook } from "@/store"
import { defineStore } from "pinia"
import { baseWsUrl, getToken, dispatchEvents } from "@/utils";
import router from "@/router";


let isWebSocketOpen = false

export const useWebsocketStore = defineStore("websocket", () => {
    const ws = ref<WebSocket | null>(null);

    const initWebSocket = async () => {
        if (!ws.value && getToken()) {
            isWebSocketOpen = true;
            let wsUrl = baseWsUrl();
            wsUrl = baseWsUrl() + `?module=sys` + '&token=' + `${getToken()}`;
            ws.value = new WebSocket(wsUrl);
            setupEventListeners(ws.value);
        }
    }

    let heartbeatIntervalId: any | unknown = null;

    const setupEventListeners = (websocket: WebSocket) => {
        heartbeatIntervalId = setInterval(() => {
            if (websocket?.readyState === WebSocket.OPEN) {
                sendMessage({
                    class: 'ping'
                });
            } else if (websocket?.readyState === WebSocket.CLOSED || websocket?.readyState === WebSocket.CLOSING) {
                clearInterval(heartbeatIntervalId!);
            }
        }, 10000);
        const handleClose = (event: CloseEvent) => {
            isWebSocketOpen = false;
            if (heartbeatIntervalId) {
                clearInterval(heartbeatIntervalId);
            }
            ws.value = null;
            setTimeout(() => {
                if (!isWebSocketOpen) {
                    initWebSocket();
                }
            }, 2000);
        }
        const handleMessage = (event: MessageEvent) => {
            const res = JSON.parse(event.data);
            if (res.code == 701) {
                useAdminStoreHook().resetToken()
                useAdminStoreHook().setInfo({})
                router.replace({
                    path: "/login",
                    query: {
                        redirect: router.currentRoute.value.fullPath
                    }
                });
            }else if (res.code == 1){
                dispatchEvents('websocketMessage', res);
            }
        }
        const handleOpen = (event: Event) => {
            sendMessage({});
        }
        const handleError = (error: Event | ErrorEvent) => {
            isWebSocketOpen = false;
            if (heartbeatIntervalId) {
                clearInterval(heartbeatIntervalId);
            }
            ws.value = null;
            setTimeout(() => {
                if (!isWebSocketOpen) {
                    initWebSocket();
                }
            }, 3000);
        }
        websocket.addEventListener('open', handleOpen);
        websocket.addEventListener('message', handleMessage);
        websocket.addEventListener('error', handleError);
        websocket.addEventListener('close', handleClose)
    }

    const sendMessage = (data: any) => {
        if (ws.value?.readyState === WebSocket.OPEN) {
            ws.value.send(JSON.stringify(data));
        }
    }
    return { ws, initWebSocket, sendMessage }
})
/** 在 setup 外使用 */
export function useWebsocketStoreHook() {
    return useWebsocketStore(store)
}
