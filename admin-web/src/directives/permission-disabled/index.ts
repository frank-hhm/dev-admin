import { type Directive } from "vue"
import { usePermissionStoreHook } from "@/store"
/** 权限指令 */
export const permissionDisabled: Directive = {
    mounted(el: any, binding: any, vnode: any) {
        const { value } = binding
        const roleAction = usePermissionStoreHook().roleAction
        if (value &&  typeof roleAction != 'number') {
            const permissionRoles = value
            const hasPermission = roleAction.some((role) => {
                return permissionRoles.includes(role)
            })
            if (!hasPermission) {
                el.disabled = true
            }
        } else if (roleAction !== -1) {
            // throw new Error(`need roles! Like v-permission`)
        }
    }
}
