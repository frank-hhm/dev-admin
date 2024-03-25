<template>
    <div :style="styles" class="map-body" v-loading="initLoading">
        <div v-show="!initStatus && !initLoading">
            <div class="text-red">初始化组件失败</div>
        </div>
        <div class="map-search-box" v-show="!initLoading && !isSetHeight">
            <a-input class="map-search-input" v-model="searchText" id="searchInput" placeholder="输入关键字搜索" allow-clear />
            <div class="map-search-list" v-if="searchList.length > 0">
                <template v-for="(item, index) in searchList" :key="index">
                    <div class="map-search-list-item" @click="setSearchItem(item)">
                        <div class="t">{{ item.title }}</div>
                        <div class="mt5 text-grey fz12">{{ item.address }}</div>
                    </div>
                </template>
            </div>
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
            <div class="map-select-box" v-if="selectedData.title" v-show="!isSetHeight">
                <div>
                    经纬度:{{ selectedData.location.lng }},{{ selectedData.location.lat }}
                </div>
                <div class="map-select-box-name">
                    {{ selectedData.title }}
                </div>
            </div>
        </div>
        <!--  -->
        <div class="map-address-body" v-if="!initLoading" v-loading="searchLoading">
            <div class="map-address" v-show="!isSetHeight">
                <template v-if="lists.length > 0">
                    <div class="map-address-item" :class="index == selectedIndex ? 'is-active' : ''"
                        v-for="(item, index) in lists" :key="index" @click="selectItem(item, index)">
                        <div>
                            <div class="fw map-address-name">{{ item.title }}</div>
                            <div class="text-grey flex items-center">
                                <icon-location />
                                <div>
                                    <span class="mr5">{{ item.address }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-default icon icon-gouxuan" v-if="index == selectedIndex"></div>
                    </div>
                </template>
                <div class="search-empty" v-else>
                    <p>暂无搜索结果</p>
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


interface mapCenterType {
    lat: number
    lng: number
}

const props = withDefaults(
    defineProps<{
        height?: string;
        width?: string;
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
        searchCity: "全国",
        zoom: 12,
        modelValue: "",
        valueType: "string",
        hideBtn: false,
        lat: 20.855058,
        lng: 109.720828,
    }
);

const emit = defineEmits(["change", "update:modelValue"]);

const mapHegiht = ref<string>(props.height);

const mapCenter = ref<mapCenterType>({
    lat: 20.855058,
    lng: 109.720828,
});

const mapKey = ref<string>(systemInfo.map_key || 'R2FBZ-4WD6X-E5P4Q-TK34D-F4SFV-XJFZP');

const searchCity = ref<string>(props.searchCity);

const searchCityCode = ref<string>("");

const _TMap = ref<any>();

onMounted(() => {
    if (!useAppStore().isMapScriptLoad) {
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
                        useAppStore().setMapScriptLoad()
                        _TMap.value = window?.TMap;
                        toInit();
                    }, 2000);
                })
            }
        );
    } else {
        nextTick(() => {
            _TMap.value = window?.TMap;
            toInit();
        })
    }
});

const initLoading = ref<boolean>(true);

const initStatus = ref<boolean>(false);

const lists = ref<any>([]);

const map = ref<any>();

const toInit = () => {
    //设置中心点坐标
    if(props.lat && props.lng){
        mapCenter.value = new _TMap.value.LatLng(props.lat, props.lng);
    }else{
        mapCenter.value = new _TMap.value.LatLng(mapCenter.value.lat,mapCenter.value.lng);
    }
    map.value = new _TMap.value.Map("mapContainer", {
        zoom: props.zoom,
        center: mapCenter.value
    });
    map.value.on("error", (res: any) => {
        $utils.errorMsg("地图组件初始化失败,请关闭重试");
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
            searchExplore();
        }
    })
    map.value.on("panend", () => {
        mapCenter.value = map.value.getCenter();
        addMarker(mapCenter.value.lng, mapCenter.value.lat)
        searchExplore();
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
    return marker.value;
}

const clearMap = () => {
    if (marker.value) {
        marker.value.setMap(null);
        marker.value = null;
    }
}

const searchList = ref<any>([]);

const mapSearch = () => {
    var search = new _TMap.value.service.Suggestion({
        region: searchCity.value,
        pageSize: 20
    });
    search.getSuggestions({
        keyword: searchText.value
    }).then((result: any) => {
        searchList.value = result.data;
    })
};

const setSearchItem = (item: any) => {
    mapCenter.value = new _TMap.value.LatLng(item.location.lat, item.location.lng)
    map.value.setCenter(mapCenter.value);
    addMarker(item.location.lng, item.location.lat);
    searchExplore();
}

const searchExplore = () => {
    setSearchLoading(true);
    var search = new _TMap.value.service.Search({
        pageSize: 20
    });
    search.explore({
        center: mapCenter.value,
        radius: 1000,
    }).then((result: any) => {
        lists.value = result.data;
        searchList.value = [];
        searchText.value = '';
        setTimeout(() => {
            setSearchLoading(false);
        }, 500);
    });
}

const searchText = ref<string>("");

const searchLoading = ref<boolean>(false);

const selectedIndex = ref<number | string>(-1);

const selectedData = ref<any>({});

const setSearchLoading = (v: boolean) => {
    searchLoading.value = v;
};

const selectItem = (item: any, index: number) => {
    selectedIndex.value = index;
    // 清除 marker
    clearMap();
    addMarker(item.location.lng, item.location.lat);
    selectedData.value = item;
    emit("change", {
        province: item.ad_info.province,
        city: item.ad_info.city,
        district: item.ad_info.district,
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
watch(
    () => searchText.value,
    (val, old) => {
        if (val != "") {
            mapSearch();
        }
    },
    {
        deep: true,
    }
);
</script>



<style scoped>
.map-body {
    border: 1px solid var(--color-border-1);
    border-radius: 2px;
    position: relative;
    min-height: 684px;
}

.map-box {
    width: 100%;
    border-radius: var(--base-radius);
    position: relative;
    z-index: 1;
}

.map-search-box {
    position: absolute;
    width: 240px;
    height: 32px;
    top: 10px;
    left: 10px;
    z-index: 9;
}


.map-search-input {
    z-index: 2;
    width: 100%;
    border: 1px solid #c9cdd4;
    color: #1d2129 !important;
    background-color: #f2f3f5 !important;
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
    color: rgba(var(--gray-6));
}

.map-address {
    height: 328px;
    overflow-y: scroll;
    margin-top: 10px;
}

.map-address-body {
    height: 328px;
}

.map-address-item {
    padding: 5px 10px;
    border: 1px dashed var(--color-border-1);
    margin: 5px 0;
    cursor: pointer;
    border-radius: 2px;
    line-height: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.map-address-item .map-address-name {
    color: var(--color-text-1);
}
.map-address-item:hover {
    border: 1px dashed rgb(var(--primary-6));
}

.map-address-item.is-active {
    color: rgb(var(--primary-6));
    background: rgb(var(--primary-1));
    border: 1px dashed rgb(var(--primary-6));
}

.map-address-item:hover .map-address-name {
    color: rgb(var(--primary-6));
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

.map-search-list {
    background: var(--color-white);
    padding: 10px;
    max-height: 240px;
    overflow-y: scroll;
    border-radius: var(--base-radius);
}

.map-search-list-item {
    padding: 10px 0;
    border-bottom: 1px solid var(--color-border-1);
    cursor: pointer;
}

.map-search-list-item:last-child {
    border-bottom: none;
}

.map-search-list-item:hover {
    color: rgb(var(--primary-6));
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

<style>
.map-search-input .arco-input::placeholder {
    color: #86909C !important;
}
</style>