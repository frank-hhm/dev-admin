const PREFIX = "dev-sys"

/** 缓存数据时用到的 Key */
class CacheKey {
    static TOKEN = `${PREFIX}-token-key`
    static TEMPLATE_DARK = `${PREFIX}-template-dark`
    
    static ACTIVE_THEME_NAME = `${PREFIX}-active-theme-name-key`
    static SYSTEM_INFO = `${PREFIX}-system-info-key`
    static MENUS = `${PREFIX}-menus-key`
    static ROLE_ACTION = `${PREFIX}-role-action-key`
    static ENUM = `${PREFIX}-enum-key`
    static PAGE_LIMIT = `${PREFIX}-page-limit-key`
}

export default CacheKey
