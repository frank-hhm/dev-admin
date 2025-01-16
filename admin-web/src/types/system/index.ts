export * from './admin'
export * from './menus'

export interface SystemInfoType {
    system_name: string
    system_version: string
    system_logo: string
    system_icon: string
    copyright: string
    map_key?: string
    map_secret_key?: string
}
