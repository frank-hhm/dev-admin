import{d as B,r as d,o as D,I as w,bq as A,B as E,q as C,ba as y,aP as I,c as S,w as t,bf as U,l as k,b as N,e,g as h,f as M,F as R,_ as $}from"./index.43d39ee3.js";/* empty css              */import{g as q,s as L}from"./config.c404c754.js";const P={class:"card-form-box",style:{"max-width":"400px"}},T=B({__name:"email",setup(j){const i=d("120px"),{proxy:r,proxy:{$utils:f}}=k(),p=d(),l=d({mail_imap_host:"",mail_imap_port:void 0,mail_username:"",mail_password:"",mail_default_from:"",mail_default_from_name:""}),s=d(!1),v=()=>{s.value=!0,q("email").then(u=>{l.value.mail_imap_host=String(u.data.mail_imap_host||""),u.data.mail_imap_port&&(l.value.mail_imap_port=Number(u.data.mail_imap_port)),l.value.mail_username=String(u.data.mail_username||""),l.value.mail_password=String(u.data.mail_password||""),l.value.mail_default_from=String(u.data.mail_default_from||""),l.value.mail_default_from_name=String(u.data.mail_default_from_name||""),s.value=!1},u=>{s.value=!1,f.errorMsg(u)})},_=d(!1),b=()=>{var u;(u=r==null?void 0:r.$refs.configFormRef)==null||u.validate(a=>{if(a===void 0){if(_.value)return;_.value=!0,L(l.value).then(m=>{_.value=!1,f.successMsg(m.message)},m=>{_.value=!1,f.errorMsg(m)})}})};return D(()=>{v()}),(u,a)=>{const m=w,n=R,g=A,F=E,c=C,x=y,V=U("loading");return I((N(),S(x,{title:"\u90AE\u7BB1\u914D\u7F6E",class:"card"},{default:t(()=>[e(c,{model:l.value,ref_key:"configFormRef",ref:p},{default:t(()=>[h("div",P,[e(c,{model:l.value,"laba-width":"160px",ref_key:"configFormRef",ref:p},{default:t(()=>[e(n,{"label-col-flex":i.value,label:"\u670D\u52A1\u5668\u5730\u5740",field:"mail_imap_host"},{default:t(()=>[e(m,{class:"form-input-inline",modelValue:l.value.mail_imap_host,"onUpdate:modelValue":a[0]||(a[0]=o=>l.value.mail_imap_host=o),placeholder:"\u8BF7\u8F93\u5165\u670D\u52A1\u5668\u5730\u5740","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),e(n,{"label-col-flex":i.value,label:"\u7AEF\u53E3",field:"mail_imap_port"},{default:t(()=>[e(g,{class:"form-input-inline",modelValue:l.value.mail_imap_port,"onUpdate:modelValue":a[1]||(a[1]=o=>l.value.mail_imap_port=o),placeholder:"\u8BF7\u8F93\u5165\u670D\u52A1\u5668\u7AEF\u53E3","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),e(n,{"label-col-flex":i.value,label:"\u7528\u6237\u540D",field:"mail_username"},{default:t(()=>[e(m,{class:"form-input-inline",modelValue:l.value.mail_username,"onUpdate:modelValue":a[2]||(a[2]=o=>l.value.mail_username=o),placeholder:"\u8BF7\u8F93\u5165\u670D\u52A1\u5668\u7684\u7528\u6237\u540D","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),e(n,{"label-col-flex":i.value,label:"\u5BC6\u7801",field:"mail_password"},{default:t(()=>[e(m,{class:"form-input-inline",modelValue:l.value.mail_password,"onUpdate:modelValue":a[3]||(a[3]=o=>l.value.mail_password=o),placeholder:"\u8BF7\u8F93\u5165\u670D\u52A1\u5668\u7684\u5BC6\u7801","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),e(n,{"label-col-flex":i.value,label:"\u9ED8\u8BA4\u53D1\u9001\u5730\u5740",field:"mail_default_from"},{default:t(()=>[e(m,{class:"form-input-inline",modelValue:l.value.mail_default_from,"onUpdate:modelValue":a[4]||(a[4]=o=>l.value.mail_default_from=o),placeholder:"\u8BF7\u8F93\u5165\u9ED8\u8BA4\u53D1\u9001\u90AE\u4EF6\u5730\u5740","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),e(n,{"label-col-flex":i.value,label:"\u9ED8\u8BA4\u53D1\u9001\u540D\u79F0",field:"mail_default_from_name"},{default:t(()=>[e(m,{class:"form-input-inline",modelValue:l.value.mail_default_from_name,"onUpdate:modelValue":a[5]||(a[5]=o=>l.value.mail_default_from_name=o),placeholder:"\u8BF7\u8F93\u5165\u9ED8\u8BA4\u53D1\u9001\u540D\u79F0","allow-clear":""},null,8,["modelValue"])]),_:1},8,["label-col-flex"]),e(n,{"label-col-flex":i.value},{default:t(()=>[e(F,{type:"primary",onClick:a[6]||(a[6]=o=>b()),loading:_.value,disabled:_.value},{default:t(()=>a[7]||(a[7]=[M("\u786E\u5B9A")])),_:1},8,["loading","disabled"])]),_:1},8,["label-col-flex"])]),_:1},8,["model"])])]),_:1},8,["model"])]),_:1})),[[V,s.value]])}}});const J=$(T,[["__scopeId","data-v-64e685f4"]]);export{J as default};
