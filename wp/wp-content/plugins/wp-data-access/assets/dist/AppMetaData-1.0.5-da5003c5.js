import{l as p,r as e,j as s,o as x,e as m,C as n}from"./main-1.0.5.js";import{u as j,p as y,q as E,r as g,S as h}from"./main-1.0.5-a7d73e33.js";import{b as v}from"./ActionsApp-1.0.5-194aa982.js";import C from"./Alert-1.0.5-445ce146.js";const b=({appId:o,appDbId:r,designMode:i})=>{p.debug(o,r,i);const f=j(),[c,l]=e.useState(""),[u,A]=e.useState(!1),{prepareAppStore:S}=y(o,E.APP);e.useEffect(()=>{u||d()},[r]);const d=()=>{v(r,t=>{const a=t==null?void 0:t.data;p.debug("response data",a),a.app&&a.app.app&&Array.isArray(a.app.app)&&a.app.container&&Array.isArray(a.app.container)?S(r,a,i===!0)||l(n.contactSupport):m(n.contactSupport,{variant:"error"}),A(!0)},t=>{p.error("error",t),m(t??n.contactSupport,{variant:"error"})})};return c!==""?s.jsx(e.Suspense,{children:s.jsx(C,{severity:"error",message:c,close:!0,setClose:()=>{f(x({isActive:!1,appDbId:0}))}})}):u?s.jsx(g,{appId:o}):s.jsx(h,{title:"Loading app meta data..."})};export{b as A};