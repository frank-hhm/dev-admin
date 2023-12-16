export interface IAdmin {
    id?: string
    account?: string
    avatar?: string
    real_name?: string
    level?: number
    roles?: string[] | number[]
    status?: {
        value: number | string;
        name: string;
    }
    [key: string]: any
} 
