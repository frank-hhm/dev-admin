
export interface MenusItemType {
    id?: number;
    menu_name: string;
    menu_node: string;
    menu_path: string;
    params: string;
    pid: number | string;
    [key: string]: any
}

export type MenusListsType = MenusItemType[];

