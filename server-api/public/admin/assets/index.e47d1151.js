import{r as g,a4 as C,a8 as i,aa as w,ad as B,ah as U,ai as h,aI as k,aJ as I,aj as L,aK as R,aD as E,ap as t,ar as M,ax as p,am as N,ak as l,ay as S,az as T,aL as $,aC as j}from"./index.238d0e18.js";/* empty css              */const q=()=>g({url:"system.config/get",method:"GET"}),z=f=>g({url:"system.config/save",method:"POSt",data:f}),G={class:"card-form-box"},J=C({__name:"index",setup(f){const{proxy:c,proxy:{$utils:_}}=M(),s=i("80px"),b=i(),e=i({system_name:"",system_version:"",system_logo:"",system_icon:[],web_domain:"",copyright:""}),r=i(!1),y=()=>{r.value=!0,q().then(n=>{e.value=n.data,e.value.system_icon=[],r.value=!1},n=>{r.value=!1,_.errorMsg(n)})},d=i(!1),F=()=>{var n;(n=c==null?void 0:c.$refs.configFormRef)==null||n.validate((a,m)=>{a||(d.value=!0,z(e.value).then(o=>{d.value=!1,_.successMsg(o.message)},o=>{d.value=!1,_.errorMsg(o)}))})};return w(()=>{y()}),(n,a)=>{const m=B,o=T,v=$,x=U,V=h,D=k,A=I("loading");return p(),L("div",null,[R((p(),E(D,{title:"\u7F51\u7AD9\u914D\u7F6E",class:"card"},{default:t(()=>[N("div",G,[l(V,{model:e.value,ref_key:"configFormRef",ref:b},{default:t(()=>[l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u540D\u79F0",field:"system_name"},{default:t(()=>[l(m,{modelValue:e.value.system_name,"onUpdate:modelValue":a[0]||(a[0]=u=>e.value.system_name=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9\u540D\u79F0","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u7248\u672C",field:"system_version"},{default:t(()=>[l(m,{modelValue:e.value.system_version,"onUpdate:modelValue":a[1]||(a[1]=u=>e.value.system_version=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9\u7248\u672C","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9logo",field:"system_logo"},{default:t(()=>[l(v,{modelValue:e.value.system_logo,"onUpdate:modelValue":a[2]||(a[2]=u=>e.value.system_logo=u),count:"1"},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u56FE\u6807",field:"system_icon"},{default:t(()=>[l(v,{modelValue:e.value.system_icon,"onUpdate:modelValue":a[3]||(a[3]=u=>e.value.system_icon=u),count:"8"},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9URL\u5730\u5740",field:"web_domain"},{default:t(()=>[l(m,{modelValue:e.value.web_domain,"onUpdate:modelValue":a[4]||(a[4]=u=>e.value.web_domain=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9URL","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"copyright",field:"copyright"},{default:t(()=>[l(m,{modelValue:e.value.copyright,"onUpdate:modelValue":a[5]||(a[5]=u=>e.value.copyright=u),placeholder:"\u8BF7\u8F93\u5165copyright","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value},{default:t(()=>[l(x,{type:"primary",onClick:F,loading:d.value,disabled:d.value},{default:t(()=>[S("\u4FDD\u5B58")]),_:1},8,["loading","disabled"])]),_:1},8,["label-col-flex"])]),_:1},8,["model"])])]),_:1})),[[A,r.value]])])}}});const P=j(J,[["__scopeId","data-v-ca261d80"]]);export{P as default};