import{a_ as g,a4 as B,a5 as U,a3 as v,a6 as m,aa as h,ad as I,ah as M,ai as R,b9 as k,be as E,al as S,aK as L,am as N,as as t,av as T,aD as b,ap as $,ar as l,aE as q,aM as z,an as G,aF as K,bf as O,aI as P}from"./index.200d5b7d.js";/* empty css              */const j=()=>g({url:"system.config/get",method:"GET"}),H=_=>g({url:"system.config/save",method:"POSt",data:_}),J=B({__name:"index",setup(_){const{isMobile:y}=U(v()),{proxy:c,proxy:{$utils:f}}=T(),s=m("80px"),F=m(),e=m({system_name:"",system_version:"",system_logo:"",system_icon:[],web_domain:"",copyright:""}),r=m(!1),x=()=>{r.value=!0,j().then(n=>{e.value=n.data,r.value=!1},n=>{r.value=!1,f.errorMsg(n)})},d=m(!1),V=()=>{var n;(n=c==null?void 0:c.$refs.configFormRef)==null||n.validate((a,i)=>{a||(d.value=!0,H(e.value).then(o=>{d.value=!1,v().getSystemInfo(),f.successMsg(o.message)},o=>{d.value=!1,f.errorMsg(o)}))})};return h(()=>{x()}),(n,a)=>{const i=I,o=K,p=O,D=M,A=R,w=k,C=E("loading");return b(),S("div",null,[L((b(),N(w,{title:"\u7F51\u7AD9\u914D\u7F6E",class:"card"},{default:t(()=>[$("div",{class:"card-form-box",style:z(`width:${G(y)?"calc(100% - 20px)":"400px"}`)},[l(A,{model:e.value,ref_key:"configFormRef",ref:F},{default:t(()=>[l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u540D\u79F0",field:"system_name"},{default:t(()=>[l(i,{modelValue:e.value.system_name,"onUpdate:modelValue":a[0]||(a[0]=u=>e.value.system_name=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9\u540D\u79F0","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u7248\u672C",field:"system_version"},{default:t(()=>[l(i,{modelValue:e.value.system_version,"onUpdate:modelValue":a[1]||(a[1]=u=>e.value.system_version=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9\u7248\u672C","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9logo",field:"system_logo"},{default:t(()=>[l(p,{modelValue:e.value.system_logo,"onUpdate:modelValue":a[2]||(a[2]=u=>e.value.system_logo=u),count:"1"},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9\u56FE\u6807",field:"system_icon"},{default:t(()=>[l(p,{modelValue:e.value.system_icon,"onUpdate:modelValue":a[3]||(a[3]=u=>e.value.system_icon=u),count:"1"},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"\u7F51\u7AD9URL\u5730\u5740",field:"web_domain"},{default:t(()=>[l(i,{modelValue:e.value.web_domain,"onUpdate:modelValue":a[4]||(a[4]=u=>e.value.web_domain=u),placeholder:"\u8BF7\u8F93\u5165\u7F51\u7AD9URL","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value,label:"Copyright",field:"copyright"},{default:t(()=>[l(i,{modelValue:e.value.copyright,"onUpdate:modelValue":a[5]||(a[5]=u=>e.value.copyright=u),placeholder:"\u8BF7\u8F93\u5165copyright","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),l(o,{"label-col-flex":s.value},{default:t(()=>[l(D,{type:"primary",onClick:V,loading:d.value,disabled:d.value},{default:t(()=>[q("\u4FDD\u5B58")]),_:1},8,["loading","disabled"])]),_:1},8,["label-col-flex"])]),_:1},8,["model"])],4)]),_:1})),[[C,r.value]])])}}});const X=P(J,[["__scopeId","data-v-96eb6f9a"]]);export{X as default};