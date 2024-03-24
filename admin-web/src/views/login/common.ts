import { getCurrentInstance } from "vue";
import { loadFull } from 'tsparticles'
import { Result, ResultError } from "@/types";
import { useAppStore } from "@/store";
export default function (_call: any) {

    const {
        proxy,
        proxy: { $utils },
    } = getCurrentInstance() as any;

    const particlesInit = async (engine: any) => {
        await loadFull(engine)
    }

    const particlesLoaded = async (container: any) => {
        // console.log('Particles container loaded', container)
    }

    const colorsArray = [
        "#00B42A",
        "#00B42A",
        "#FFCE56",
        "#4BC0C0",
        "#9966FF",
        "#FF8C42",
        "#2E7CE6",
        "#FFD700",
        "#A020F0",
        "#FF4500"
    ];

    const particlesOptions = {
        background: {
            color: {
                value: "#ffffff",//粒子颜色
            },
        },
        fpsLimit: 30,
        interactivity: {
            events: {
                onClick: {
                    enable: true,
                    mode: "repulse",//可用的click模式有: "push", "remove", "repulse", "bubble"。
                },
                onHover: {
                    enable: true,
                    mode: "grab",//可用的hover模式有: "grab", "repulse", "bubble"。
                },
                resize: true,
            },
            modes: {
                bubble: {
                    distance: 400,
                    duration: 2,
                    opacity: 0.8,
                    size: 40,
                },
                push: {
                    quantity: 4,
                },
                repulse: {
                    distance: 200,
                    duration: 0.4,
                },
            },
        },
        particles: {
            color: {
                value: colorsArray,
            },
            links: {
                color: colorsArray,
                distance: 100,//线条长度
                enable: true,//是否有线条
                opacity: 0.2,//线条透明度。
                width: 2,//线条宽度。
            },
            collisions: {
                enable: false,
            },
            move: {
                direction: "none",
                enable: true,
                outMode: "bounce",
                random: false,
                speed: 1,//粒子运动速度。
                straight: false,
            },
            number: {
                density: {
                    enable: true,
                    area: 800,
                },
                value: 100,//粒子数量。
            },
            opacity: {
                value: 0.2,//粒子透明度。
            },
            shape: {
                type: "circle", //可用的粒子外观类型有："circle","edge","triangle", "polygon","star"
            },
            size: {
                random: true,
                value: 10,
            },
        },
        detectRetina: true,
    };


    const getOptions = () => {
        let options = particlesOptions
        options.background.color.value = useAppStore().isDark ? "#17171a" : "#ffffff";
        return options;
    }


    return {
        particlesInit,
        particlesLoaded,
        getOptions
    };

}