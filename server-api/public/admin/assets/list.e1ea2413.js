import{a4 as O,a5 as P,a3 as K,a6 as u,a8 as W,be as L,aD as b,al as T,ar as s,as as t,aK as A,am as B,aE as C,an as w,aX as G,au as N,ad as Y,aF as Z,ai as ee,ah as j,aU as q,aV as te,av as S,by as ae,b0 as le,aa as se,bt as oe,b5 as ne,b3 as ue,bz as ie,ap as U,aq as z,b1 as re,b4 as ce}from"./index.b04076a2.js";import{c as de,u as _e,g as J,s as me,a as ve,b as pe,d as fe}from"./role.6732c049.js";const ge={name:"systemRoleCreate"},be=O({...ge,emits:["success"],setup(H,{expose:x,emit:k}){const{isMobile:$}=P(K()),{proxy:c,proxy:{$utils:h}}=S(),E=k,F=u("create"),i=u(0),p=u(!1),r=u(),d=u({role_name:"",remarks:""}),M=W({role_name:[{required:!0,message:"\u89D2\u8272\u540D\u79F0\u4E0D\u80FD\u4E3A\u7A7A\uFF01"}]}),l=u(!1),n=()=>{!i.value||(l.value=!0,J({id:i.value}).then(e=>{d.value.role_name=e.data.role_name,d.value.remarks=e.data.remarks,l.value=!1}).catch(e=>{l.value=!1,h.errorMsg(e)}))},o=u(!1),m=()=>{var e;(e=c==null?void 0:c.$refs.createRef)==null||e.validate((a,y)=>{if(console.log(a),!a){if(o.value)return;o.value=!0;let f=null;F.value=="create"?f=de(d.value):f=_e(Object.assign({id:i.value},d.value)),f.then(v=>{h.successMsg(v.message),D(),E("success"),o.value=!1}).catch(v=>{h.errorMsg(v),o.value=!1})}})},I=(e=0)=>{e!=0?(F.value="update",i.value=e):F.value="create",N(()=>{n()}),p.value=!0},D=()=>{var e;return(e=c==null?void 0:c.$refs.createRef)==null||e.resetFields(),i.value=0,p.value=!1,!0};return x({open:I}),(e,a)=>{const y=Y,f=Z,v=ee,R=j,_=q,g=te,Q=L("loading"),X=L("btn");return b(),T("div",null,[s(g,{title:F.value=="create"?"\u6DFB\u52A0\u89D2\u8272":"\u7F16\u8F91\u89D2\u8272",onBeforeOk:m,onBeforeCancel:D,width:w($)?"calc(100% - 20px)":"400px",top:w(G)().ModalTop,class:"modal",visible:p.value,"onUpdate:visible":a[2]||(a[2]=V=>p.value=V),"align-center":!1,"title-align":"start","render-to-body":""},{footer:t(()=>[s(_,null,{default:t(()=>[A((b(),B(R,{onClick:D},{default:t(()=>[C("\u53D6\u6D88")]),_:1})),[[X]]),A((b(),B(R,{type:"primary",disabled:l.value||o.value,loading:o.value,onClick:m},{default:t(()=>[C("\u786E\u5B9A")]),_:1},8,["disabled","loading"])),[[X]])]),_:1})]),default:t(()=>[A((b(),B(v,{layout:"vertical",model:d.value,ref_key:"createRef",ref:r,rules:M},{default:t(()=>[s(f,{label:"\u89D2\u8272\u540D\u79F0\uFF1A",field:"role_name"},{default:t(()=>[s(y,{modelValue:d.value.role_name,"onUpdate:modelValue":a[0]||(a[0]=V=>d.value.role_name=V),placeholder:"\u8BF7\u8F93\u5165\u89D2\u8272\u540D\u79F0"},null,8,["modelValue"])]),_:1}),s(f,{label:"\u5907\u6CE8\uFF1A",field:"remarks"},{default:t(()=>[s(y,{modelValue:d.value.remarks,"onUpdate:modelValue":a[1]||(a[1]=V=>d.value.remarks=V),placeholder:"\u5907\u6CE8"},null,8,["modelValue"])]),_:1})]),_:1},8,["model","rules"])),[[Q,l.value]])]),_:1},8,["title","width","top","visible"])])}}});const ke={name:"systemRoleAuth"},he=O({...ke,emits:["success"],setup(H,{expose:x,emit:k}){const{isMobile:$}=P(K()),{proxy:c,proxy:{$utils:h}}=S(),E=k,F=u(),i=u([]),p=u(!0),r=u([]),d=()=>{p.value=!0,ve().then(e=>{i.value=e.data,N(()=>{var a;(a=c==null?void 0:c.$refs.treeRef)==null||a.expandAll()})}).catch(e=>{}),J({id:o.value}).then(e=>{r.value=M(e.data.rules),p.value=!1}).catch(e=>{h.errorMsg(e)})},M=e=>{let a=[];return e.forEach(y=>{a.push(Number(y))}),a},l=u(!1),n=()=>{l.value||(l.value=!0,me({id:o.value,rules:r.value}).then(e=>{h.successMsg(e.message),E("success"),l.value=!1,D()}).catch(e=>{h.errorMsg(e),l.value=!1}))},o=u(0),m=u(!1),I=(e=0)=>{o.value=e,N(()=>{d()}),m.value=!0},D=()=>{o.value=0,r.value=[],m.value=!1};return x({open:I}),(e,a)=>{const y=ae,f=j,v=q,R=le,_=L("loading");return b(),T("div",null,[s(R,{class:"drawer-default",title:"\u6388\u6743\u8BBE\u7F6E",visible:m.value,"onUpdate:visible":a[3]||(a[3]=g=>m.value=g),width:w($)?"calc(100% - 20px)":"500px"},{default:t(()=>[A((b(),T("div",null,[s(y,{data:i.value,ref_key:"treeRef",ref:F,checkable:!0,"selected-keys":r.value,"onUpdate:selectedKeys":a[0]||(a[0]=g=>r.value=g),"checked-keys":r.value,"onUpdate:checkedKeys":a[1]||(a[1]=g=>r.value=g),"check-strictly":!0,fieldNames:{key:"id",title:"label",children:"children"},multiple:""},null,8,["data","selected-keys","checked-keys"])])),[[_,p.value]])]),footer:t(()=>[s(v,null,{default:t(()=>[s(f,{onClick:D},{default:t(()=>[C("\u53D6\u6D88")]),_:1}),s(f,{onClick:a[2]||(a[2]=g=>n()),type:"primary",loading:l.value,disabled:p.value||l.value},{default:t(()=>[C("\u786E\u5B9A")]),_:1},8,["loading","disabled"])]),_:1})]),_:1},8,["visible","width"])])}}}),Fe={class:"text-grey"},ye={class:"text-grey"},De=O({__name:"list",setup(H){const{isMobile:x}=P(K()),{proxy:k}=S(),{proxy:{$utils:$}}=S(),c=u(!0),h=u([]),E=u(),F=l=>{k==null||k.$refs.createComponentRef.open(l)},i=u({total:0,limit:G().PageLimit.value,page:1}),p=l=>{i.value=l,r()},r=(l=!1)=>{l&&(i.value.page=1),c.value=!0;let n={};n.page=i.value.page,n.limit=i.value.limit,pe(n).then(o=>{h.value=o.data.data,i.value.total=o.data.total,setTimeout(()=>{c.value=!1},300)}).catch(o=>{$.errorMsg(o)})},d=l=>{fe({id:l}).then(n=>{r(),$.successMsg(n.message)}).catch(n=>{$.errorMsg(n)})},M=l=>{k==null||k.$refs.systemRoleAuthRef.open(l)};return se(()=>{r()}),(l,n)=>{const o=j,m=re,I=oe,D=ne,e=q,a=ue,y=ce,f=ie,v=L("permission");return b(),B(f,{pageHeader:""},{"page-header-left":t(()=>[C(" \u5217\u8868 ")]),"page-header-right":t(()=>[A((b(),B(o,{type:"text",size:"small",onClick:n[0]||(n[0]=R=>F(0))},{default:t(()=>[C(" \u6DFB\u52A0\u89D2\u8272 ")]),_:1})),[[v,"system-role-create"]]),s(be,{ref_key:"createComponentRef",ref:E,onSuccess:n[1]||(n[1]=R=>r(!0))},null,512),s(he,{ref:"systemRoleAuthRef",onSuccess:n[2]||(n[2]=R=>r(!0))},null,512)]),content:t(({height:R})=>[s(a,{loading:c.value,data:h.value,"row-key":"id",isLeaf:"",pagination:!1,scroll:{x:"100%",y:R-39}},{columns:t(()=>[s(m,{title:"\u89D2\u8272\u540D\u79F0","data-index":"role_name",width:w(x)?160:void 0},{cell:t(({record:_})=>[U("span",null,z(_.role_name),1)]),_:1},8,["width"]),s(m,{title:"\u5907\u6CE8","data-index":"remarks",width:w(x)?160:void 0},{cell:t(({record:_})=>[U("span",Fe,z(_.remarks||"--"),1)]),_:1},8,["width"]),s(m,{title:"\u521B\u5EFA\u65F6\u95F4","data-index":"create_time",width:160},{cell:t(({record:_})=>[U("span",ye,z(_.create_time),1)]),_:1}),s(m,{title:"\u64CD\u4F5C",align:"right",fixed:w(x)?void 0:"right",width:180},{cell:t(({record:_})=>[s(e,null,{default:t(()=>[A((b(),B(o,{onClick:g=>M(_.id),size:"small"},{default:t(()=>[C("\u6388\u6743\u8BBE\u7F6E")]),_:2},1032,["onClick"])),[[v,"system-role-auth"]]),A((b(),B(o,{onClick:g=>F(_.id),size:"small"},{default:t(()=>[C("\u7F16\u8F91")]),_:2},1032,["onClick"])),[[v,"system-role-update"]]),A((b(),T("div",null,[s(D,{content:"\u786E\u5B9A\u5220\u9664\u5417\uFF1F",onOk:g=>d(_.id)},{icon:t(()=>[s(I,{type:"red"})]),default:t(()=>[s(o,{size:"small"},{default:t(()=>[C("\u5220\u9664")]),_:1})]),_:2},1032,["onOk"])])),[[v,"system-role-delete"]])]),_:2},1024)]),_:1},8,["fixed"])]),_:2},1032,["loading","data","scroll"])]),footer:t(()=>[s(y,{listPage:i.value,onChange:p},null,8,["listPage"])]),_:1})}}});export{De as default};
