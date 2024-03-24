export const eventsListener = (event: string, callback: (event: CustomEvent) => void) => {
    window.addEventListener(event, (e: any) => {
        callback(e);
    });
};

export const dispatchEvents = (event: string, detail?: any) => {
    const customEvent = new CustomEvent(event, { detail });
    window.dispatchEvent(customEvent);
};


export default {
    eventsListener,
    dispatchEvents
}