import{_ as X}from"./LayoutBody.274d7748.js";import{a4 as U,a6 as u,a8 as Y,b9 as L,aD as g,al as V,ar as s,as as a,aN as h,am as $,aE as D,an as G,aY as j,au as S,ad as H,aF as J,ai as Q,ah as O,aW as P,aX as Z,av as T,bu as ee,bv as te,aa as ae,bo as le,a$ as se,bp as ne,ap as I,aq as N,bt as oe,bw as ue}from"./index.1e9f5eaa.js";import{c as ie,u as re,g as q,s as ce,a as de,b as _e,d as me}from"./role.76f52e9b.js";const ve={name:"systemRoleCreate"},pe=U({...ve,emits:["success"],setup(z,{expose:k,emit:A}){const{proxy:d,proxy:{$utils:b}}=T(),E=A,F=u("create"),r=u(0),p=u(!1),c=u(),_=u({role_name:"",remarks:""}),x=Y({role_name:[{required:!0,message:"\u89D2\u8272\u540D\u79F0\u4E0D\u80FD\u4E3A\u7A7A\uFF01"}]}),l=u(!1),o=()=>{!r.value||(l.value=!0,q({id:r.value}).then(e=>{_.value.role_name=e.data.role_name,_.value.remarks=e.data.remarks,l.value=!1}).catch(e=>{l.value=!1,b.errorMsg(e)}))},n=u(!1),m=()=>{var e;(e=d==null?void 0:d.$refs.createRef)==null||e.validate((t,y)=>{if(console.log(t),!t){if(n.value)return;n.value=!0;let f=null;F.value=="create"?f=ie(_.value):f=re(Object.assign({id:r.value},_.value)),f.then(v=>{b.successMsg(v.message),C(),E("success"),n.value=!1}).catch(v=>{b.errorMsg(v),n.value=!1})}})},w=(e=0)=>{e!=0?(F.value="update",r.value=e):F.value="create",S(()=>{o()}),p.value=!0},C=()=>{var e;return(e=d==null?void 0:d.$refs.createRef)==null||e.resetFields(),r.value=0,p.value=!1,!0};return k({open:w}),(e,t)=>{const y=H,f=J,v=Q,i=O,B=P,R=Z,W=L("loading"),K=L("btn");return g(),V("div",null,[s(R,{title:F.value=="create"?"\u6DFB\u52A0\u89D2\u8272":"\u7F16\u8F91\u89D2\u8272",onBeforeOk:m,onBeforeCancel:C,width:"400px",top:G(j)().ModalTop,class:"modal",visible:p.value,"onUpdate:visible":t[2]||(t[2]=M=>p.value=M),"align-center":!1,"title-align":"start","render-to-body":""},{footer:a(()=>[s(B,null,{default:a(()=>[h((g(),$(i,{onClick:C},{default:a(()=>[D("\u53D6\u6D88")]),_:1})),[[K]]),h((g(),$(i,{type:"primary",disabled:l.value||n.value,loading:n.value,onClick:m},{default:a(()=>[D("\u786E\u5B9A")]),_:1},8,["disabled","loading"])),[[K]])]),_:1})]),default:a(()=>[h((g(),$(v,{layout:"vertical",model:_.value,ref_key:"createRef",ref:c,rules:x},{default:a(()=>[s(f,{label:"\u89D2\u8272\u540D\u79F0\uFF1A",field:"role_name"},{default:a(()=>[s(y,{modelValue:_.value.role_name,"onUpdate:modelValue":t[0]||(t[0]=M=>_.value.role_name=M),placeholder:"\u8BF7\u8F93\u5165\u89D2\u8272\u540D\u79F0"},null,8,["modelValue"])]),_:1}),s(f,{label:"\u5907\u6CE8\uFF1A",field:"remarks"},{default:a(()=>[s(y,{modelValue:_.value.remarks,"onUpdate:modelValue":t[1]||(t[1]=M=>_.value.remarks=M),placeholder:"\u5907\u6CE8"},null,8,["modelValue"])]),_:1})]),_:1},8,["model","rules"])),[[W,l.value]])]),_:1},8,["title","top","visible"])])}}});const fe={name:"systemRoleAuth"},ge=U({...fe,emits:["success"],setup(z,{expose:k,emit:A}){const{proxy:d,proxy:{$utils:b}}=T(),E=A,F=u(),r=u([]),p=u(!0),c=u([]),_=()=>{p.value=!0,de().then(e=>{r.value=e.data,S(()=>{var t;(t=d==null?void 0:d.$refs.treeRef)==null||t.expandAll()})}).catch(e=>{}),q({id:n.value}).then(e=>{c.value=x(e.data.rules),p.value=!1}).catch(e=>{b.errorMsg(e)})},x=e=>{let t=[];return e.forEach(y=>{t.push(Number(y))}),t},l=u(!1),o=()=>{l.value||(l.value=!0,ce({id:n.value,rules:c.value}).then(e=>{b.successMsg(e.message),E("success"),l.value=!1,C()}).catch(e=>{b.errorMsg(e),l.value=!1}))},n=u(0),m=u(!1),w=(e=0)=>{n.value=e,S(()=>{_()}),m.value=!0},C=()=>{n.value=0,c.value=[],m.value=!1};return k({open:w}),(e,t)=>{const y=ee,f=O,v=P,i=te,B=L("loading");return g(),V("div",null,[s(i,{class:"drawer-default",title:"\u6388\u6743\u8BBE\u7F6E",visible:m.value,"onUpdate:visible":t[3]||(t[3]=R=>m.value=R),width:500},{default:a(()=>[h((g(),V("div",null,[s(y,{data:r.value,ref_key:"treeRef",ref:F,checkable:!0,"selected-keys":c.value,"onUpdate:selectedKeys":t[0]||(t[0]=R=>c.value=R),"checked-keys":c.value,"onUpdate:checkedKeys":t[1]||(t[1]=R=>c.value=R),"check-strictly":!0,fieldNames:{key:"id",title:"label",children:"children"},multiple:""},null,8,["data","selected-keys","checked-keys"])])),[[B,p.value]])]),footer:a(()=>[s(v,null,{default:a(()=>[s(f,{onClick:C},{default:a(()=>[D("\u53D6\u6D88")]),_:1}),s(f,{onClick:t[2]||(t[2]=R=>o()),type:"primary",loading:l.value,disabled:p.value||l.value},{default:a(()=>[D("\u786E\u5B9A")]),_:1},8,["loading","disabled"])]),_:1})]),_:1},8,["visible"])])}}}),ke={class:"text-grey"},be={class:"text-grey"},Fe={class:"mt20 flex end"},he=U({__name:"list",setup(z){const{proxy:k}=T(),{proxy:{$utils:A}}=T(),d=u(!0),b=u([]),E=u(),F=l=>{k==null||k.$refs.createComponentRef.open(l)},r=u({total:0,limit:j().PageLimit.value,page:1}),p=l=>{r.value=l,c()},c=(l=!1)=>{l&&(r.value.page=1),d.value=!0;let o={};o.page=r.value.page,o.limit=r.value.limit,_e(o).then(n=>{b.value=n.data.data,r.value.total=n.data.total,setTimeout(()=>{d.value=!1},300)}).catch(n=>{A.errorMsg(n)})},_=l=>{me({id:l}).then(o=>{c(),A.successMsg(o.message)}).catch(o=>{A.errorMsg(o)})},x=l=>{k==null||k.$refs.systemRoleAuthRef.open(l)};return ae(()=>{c()}),(l,o)=>{const n=O,m=oe,w=le,C=se,e=P,t=ne,y=ue,f=X,v=L("permission");return g(),$(f,null,{default:a(()=>[I("div",null,[h((g(),$(n,{type:"primary",onClick:o[0]||(o[0]=i=>F(0))},{default:a(()=>[D(" \u6DFB\u52A0\u89D2\u8272 ")]),_:1})),[[v,"system-role-create"]])]),s(pe,{ref_key:"createComponentRef",ref:E,onSuccess:o[1]||(o[1]=i=>c(!0))},null,512),s(ge,{ref:"systemRoleAuthRef",onSuccess:o[2]||(o[2]=i=>c(!0))},null,512),s(t,{loading:d.value,class:"mt12",data:b.value,"row-key":"id",isLeaf:"",pagination:!1},{columns:a(()=>[s(m,{title:"\u89D2\u8272\u540D\u79F0","data-index":"role_name"},{cell:a(({record:i})=>[I("span",null,N(i.role_name),1)]),_:1}),s(m,{title:"\u5907\u6CE8","data-index":"remarks"},{cell:a(({record:i})=>[I("span",ke,N(i.remarks),1)]),_:1}),s(m,{title:"\u521B\u5EFA\u65F6\u95F4","data-index":"create_time",width:160},{cell:a(({record:i})=>[I("span",be,N(i.create_time),1)]),_:1}),s(m,{title:"\u64CD\u4F5C",align:"center",width:140},{cell:a(({record:i})=>[s(e,null,{default:a(()=>[h((g(),$(n,{onClick:B=>x(i.id),size:"small"},{default:a(()=>[D("\u6388\u6743\u8BBE\u7F6E")]),_:2},1032,["onClick"])),[[v,"system-role-auth"]]),h((g(),$(n,{onClick:B=>F(i.id),size:"small"},{default:a(()=>[D("\u7F16\u8F91")]),_:2},1032,["onClick"])),[[v,"system-role-update"]]),h((g(),V("div",null,[s(C,{content:"\u786E\u5B9A\u5220\u9664\u5417\uFF1F",onOk:B=>_(i.id)},{icon:a(()=>[s(w,{type:"red"})]),default:a(()=>[s(n,{size:"small"},{default:a(()=>[D("\u5220\u9664")]),_:1})]),_:2},1032,["onOk"])])),[[v,"system-role-delete"]])]),_:2},1024)]),_:1})]),_:1},8,["loading","data"]),I("div",Fe,[s(y,{listPage:r.value,onChange:p},null,8,["listPage"])])]),_:1})}}});export{he as default};
