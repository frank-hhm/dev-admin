import{b7 as b,a4 as C,a6 as i,aa as w,ad as B,ah as U,ai as h,b2 as I,b8 as k,al as E,aK as R,am as L,as as t,av as M,aD as p,ap as S,ar as l,aE as N,a3 as T,aF as $,b9 as q,aI as G}from"./index.f27941d7.js";/* empty css              */const K=()=>b({url:"system.config/get",method:"GET"}),O=f=>b({url:"system.config/save",method:"POSt",data:f}),P={class:"card-form-box"},j=C({__name:"index",setup(f){const{proxy:c,proxy:{$utils:_}}=M(),s=i("80px"),g=i(),e=i({system_name:"",system_version:"",system_logo:"",system_icon:[],web_domain:"",copyright:""}),r=i(!1),y=()=>{r.value=!0,K().then(n=>{e.value=n.data,r.value=!1},n=>{r.value=!1,_.errorMsg(n)})},d=i(!1),F=()=>{var n;(n=c==null?void 0:c.$refs.configFormRef)==null||n.validate((a,m)=>{a||(d.value=!0,O(e.value).then(o=>{d.value=!1,T().getSystemInfo(),_.successMsg(o.message)},o=>{d.value=!1,_.errorMsg(o)}))})};return w(()=>{y()}),(n,a)=>{const m=B,o=$,v=q,x=U,V=h,D=I,A=k("loading");return p(),E("div",null,[R((p(),L(D,{title:"\u7F51\u7AD9\u914D\u7F6E",class:"card"},{default:t(()=>[S("div",P,[l(V,{model:e.value,ref_key:"configFormRef",ref:g},{default:t(()=>[l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u540D\u79F0",field:"system_name"},{default:t(()=>[l(m,{modelValue:e.value.system_name,"onUpdate:modelValue":a[0]||(a[0]=u=>e.value.system_name=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9\u540D\u79F0","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u7248\u672C",field:"system_version"},{default:t(()=>[l(m,{modelValue:e.value.system_version,"onUpdate:modelValue":a[1]||(a[1]=u=>e.value.system_version=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9\u7248\u672C","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9logo",field:"system_logo"},{default:t(()=>[l(v,{modelValue:e.value.system_logo,"onUpdate:modelValue":a[2]||(a[2]=u=>e.value.system_logo=u),count:"1"},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u56FE\u6807",field:"system_icon"},{default:t(()=>[l(v,{modelValue:e.value.system_icon,"onUpdate:modelValue":a[3]||(a[3]=u=>e.value.system_icon=u),count:"1"},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9URL\u5730\u5740",field:"web_domain"},{default:t(()=>[l(m,{modelValue:e.value.web_domain,"onUpdate:modelValue":a[4]||(a[4]=u=>e.value.web_domain=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9URL","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"Copyright",field:"copyright"},{default:t(()=>[l(m,{modelValue:e.value.copyright,"onUpdate:modelValue":a[5]||(a[5]=u=>e.value.copyright=u),placeholder:"\u8BF7\u8F93\u5165copyright","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value},{default:t(()=>[l(x,{type:"primary",onClick:F,loading:d.value,disabled:d.value},{default:t(()=>[N("\u4FDD\u5B58")]),_:1},8,["loading","disabled"])]),_:1},8,["label-col-flex"])]),_:1},8,["model"])])]),_:1})),[[A,r.value]])])}}});const J=G(j,[["__scopeId","data-v-2d15c29b"]]);export{J as default};
