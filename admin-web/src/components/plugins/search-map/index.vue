<template>
    <div style="[`height: ${mapHegiht}px`]">
        <div :style="styles" class="map-body" v-loading="initLoading">
            <div v-show="!initStatus && !initLoading">
                <div class="text-red">初始化组件失败</div>
            </div>
            <div class="map-search-box" v-show="!initLoading && !isSetHeight">
                <a-input-search class="map-search-input" v-model="searchText" @search="mapSearch(false)" id="searchInput"
                    placeholder="输入关键字搜索" allow-clear />
            </div>
            <!-- 地图 -->
            <div class="map-box" :style="{ height: 'calc(' + mapHegiht + ' - 340px)' }">
                <div class="flex items-center" v-if="hideBtn">
                    <div class="map-hide-btn icon icon-fangwen" @click="setHeight"></div>
                    <div class="ml50 mt10 text-grey" v-show="isSetHeight">
                        地图已隐藏，点击左边按钮可展示
                    </div>
                </div>
                <div class="map-box-main" id="mapContainer"></div>
                <!--  -->
                <div class="map-select-box" v-if="selectedData.name" v-show="!isSetHeight">
                    <div>
                        经纬度:{{ selectedData.location.lng }},{{ selectedData.location.lat }}
                    </div>
                    <div class="map-select-box-name">
                        {{ selectedData.name }}{{ selectedData.name }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts" name="ck-search-map">
import {
    onMounted,
    ref,
    getCurrentInstance,
    nextTick,
    computed,
    StyleValue,
    watch,
} from "vue";
import { useAppStore } from "@/store";

const { systemInfo } = useAppStore();

const {
    proxy,
    proxy: { $utils },
} = getCurrentInstance() as any;

const props = withDefaults(
    defineProps<{
        height?: string;
        width?: string;
        center?: Array<number>;
        searchCity?: string;
        zoom?: number;
        modelValue?: string | number[] | string[];
        valueType?: string;
        hideBtn?: boolean;
        lat?: number;
        lng?: number;
    }>(),
    {
        height: "100%",
        width: "100%",
        center: () => {
            return [0, 0];
        },
        searchCity: "全国",
        zoom: 12,
        modelValue: "",
        valueType: "string",
        hideBtn: false,
        lat: 26.461937,
        lng: 106.324859,
    }
);

const emit = defineEmits(["change", "update:modelValue"]);


const mapHegiht = ref<string>(props.height);

const mapCenter = ref<number[]>(props.center);

const mapKey = ref<string>(systemInfo.map_key || 'R2FBZ-4WD6X-E5P4Q-TK34D-F4SFV-XJFZP');

const searchCity = ref<string>(props.searchCity);

const searchCityCode = ref<string>("");

const _TMap = ref<any>();

onMounted(() => {
    $utils.getScript(
        "https://map.qq.com/api/gljs?v=1.exp&key=" +
        mapKey.value +
        "&libraries=service",
        () => {
            nextTick(() => {
                window!._TMapSecurityConfig = {
                    securityJsCode: systemInfo.map_secret_key
                };
                setTimeout(() => {
                    _TMap.value = window?.TMap;
                    toInit();
                }, 2000);
            })
        }
    );
});

const initLoading = ref<boolean>(true);

const initStatus = ref<boolean>(false);

const lists = ref<any>([]);

const map = ref<any>();

const toInit = () => {
    //设置中心点坐标
    mapCenter.value = new _TMap.value.LatLng(props.lat, props.lng);
    map.value = new _TMap.value.Map("mapContainer", {
        zoom: props.zoom,
        center: mapCenter.value
    });
    map.value.on("error", (res: any) => {
        $utils.errorMsg("地图组件初始化失败,请关闭重试");
        console.log(res)
        initStatus.value = false;
        initLoading.value = false
        return false;
        // 在此处执行处理加载失败的逻辑
    });
    map.value.on("idle", () => {
        if (!initStatus.value) {
            initLoading.value = false
            console.log("地图完成");
            $utils.successMsg("地图完成");
            initStatus.value = true;
            addMarker(props.lng, props.lat)
        }
    })
};

const marker = ref<any>();

const addMarker = (g: number, t: number) => {
    clearMap();
    marker.value = new _TMap.value.MultiMarker({
        map: map.value,
        geometries: [{
            "position": new _TMap.value.LatLng(t, g),
        }]
    });
    console.log(marker.value)
    return marker.value;
}

const clearMap = () => {
    if (marker.value) {
        marker.value.setMap(null);
        marker.value = null;
    }
}

const Autocomplete = () => {
    _TMap.value.event.addListener(
        new _TMap.value.Autocomplete({
            city: searchCity.value,
            input: "searchInput",
        }),
        "select",
        (res: any) => {
            if (res.type == "select") {
                searchCityCode.value = res.poi.adcode
                searchCity.value = res.poi.adcode
                searchText.value = res.poi.name;
                mapCenter.value = [res.poi.location.lng, res.poi.location.lat];
                mapSearch();
            }
        }
    );
};

const mapSearch = (isNear: boolean = false, isAll: boolean = false) => {
    var search = new _TMap.value.service.Search({ pageSize: 10 });
    search
        .searchRectangle({
            keyword: searchText.value,
            bounds: map.value.getBounds(),
        })
        .then((result:any) => {
            console.log(result)
        })
};

const searchText = ref<string>("");

const searchLoading = ref<boolean>(false);

const selectedIndex = ref<number | string>(-1);

const selectedData = ref<any>({});

const setSearchLoading = (v: boolean) => {
    setTimeout(() => {
        searchLoading.value = v;
    }, 300);
};

const markerItem = ref<any>();

const selectItem = (item: any, index: number) => {
    selectedIndex.value = index;
    // 清除 marker
    map.value.clearMap();
    var marker = new _TMap.value.Marker({
        icon: new _TMap.value.Icon({
            image: $utils.staticImgPath("marker_01.png"),
            size: new _TMap.value.Size(18, 30),
            imageSize: new _TMap.value.Size(18, 30),
        }),
        position: [item.location.lng, item.location.lat],
    });
    map.value.add(marker);
    map.value.setCenter([item.location.lng, item.location.lat]);
    map.value.setZoom(props.zoom);
    selectedData.value = item;
    emit("change", {
        province: item.pname,
        city: item.cityname,
        district: item.adname,
        location_lat: item.location.lat,
        location_lng: item.location.lng,
        address: item.address,
    });
    emit(
        "update:modelValue",
        props.valueType == "string"
            ? item.location.lng + "," + item.location.lat
            : [item.location.lng, item.location.lat]
    );
};
watch(
    () => searchText.value,
    (val, old) => {
        if (val == "" && old != "") {
            mapSearch();
        }
    },
    {
        deep: true,
    }
);

const styles = ref<any>([
    `width: ${props.width}`,
    `height: ${mapHegiht.value}`,
]);

const isSetHeight = ref<boolean>(false);

const setHeight = () => {
    if (!isSetHeight.value) {
        mapHegiht.value = `50px`;
    } else {
        mapHegiht.value = props.height;
    }
    styles.value = [`width: ${props.width}`, `height: ${mapHegiht.value}`];
    isSetHeight.value = !isSetHeight.value;
};
</script>



<style scoped>
.map-body {
    border: 1px solid var(--el-border-color-lighter);
    border-radius: 2px;
    position: relative;
}

.map-box {
    width: 100%;
    border-bottom: 1px solid var(--color-border-1);
    position: relative;
    z-index: 1;
}

.map-search-box {
    position: absolute;
    width: 280px;
    height: 32px;
    top: 10px;
    left: 10px;
    z-index: 9;
}


.map-search-input {
    z-index: 2;
    width: 100%;
    border: 1px solid var(--color-border-3);
}

.map-search-input:hover {
    border: 1px solid rgba(var(--primary-6));
}

.map-box-main {
    height: 100%;
    width: 100%;
}

.search-empty {
    text-align: center;
    line-height: 100px;
    color: var(--base-grey);
}

.map-address {
    height: 328px;
    overflow-y: scroll;
    margin-top: 10px;
}

.map-address-item {
    padding: 5px 10px;
    border: 1px dashed var(--color-border-1);
    margin: 5px;
    cursor: pointer;
    border-radius: 2px;
    line-height: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.map-address-item:hover {
    border: 1px dashed var(--base-default);
}

.map-address-item.is-active {
    color: var(--base-default);
    background: var(--base-default-dark);
    border: 1px dashed var(--base-default);
}

.map-address-item:hover .map-address-name {
    color: var(--base-default);
}

.map-select-box {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 2px;
    height: 40px;
    width: 200px;
    color: #fff;
    padding: 5px 10px;
    line-height: 20px;
    font-size: 12px;
}

.map-select-box-name {
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}

.map-hide-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
    height: 30px;
    line-height: 30px;
    width: 30px;
    border-radius: 30px;
    background: #fff;
    color: var(--base-grey);
    text-align: center;
    box-shadow: 0px 2px 6px 0px rgb(0 21 41 / 10%);
    cursor: pointer;
}
</style>
    