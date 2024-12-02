import router from "@/router";
import { nextTick, ref } from "vue";
export default function (_call: any) {

    const parentPath = ref<string[]>([]);
    const getParent = (menus: any[]) => {
        menus.forEach((item: any) => {
            if (item.menu_path != router.currentRoute.value.path && item.children) {
                parentPath.value = getParentPath(item.children);
                parentPath.value.push(item.menu_path)
            }
        });
        if (typeof _call == "function") {
            nextTick(() => {
                _call(parentPath.value)
            });
            return false;
        }
        return parentPath.value;
    }

    const getParentPath = (menuList: any[]) => {
        var path: string[] = [];
        menuList.forEach((item: any) => {
            if (item.menu_path != router.currentRoute.value.path && item.children) {
                let _path: string[] = getParentPath(item.children)
                path = path.concat(_path)
                path.push(item.menu_path)
            }
        });
        return path;
    }

    return {
        getParent,
        getParentPath
    };

}