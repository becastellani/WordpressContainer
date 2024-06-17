import{l as T,j as e,db as S,b2 as H,c5 as i,ay as P}from"./main-1.0.5.js";import{a2 as v}from"./main-1.0.5-a7d73e33.js";import{F as _,I as U,S as k,a as d,b as w}from"./TextField-1.0.5-7ecf712b.js";import{M as I}from"./MenuItem-1.0.5-151020c5.js";import{E as O}from"./EnumTypeEnum-1.0.5-7ffcf33a.js";import{R as W}from"./RadioGroup-1.0.5-2e2c1c10.js";import{F as Z}from"./FormControlLabel-1.0.5-d0651878.js";import{R as $}from"./Radio-1.0.5-9d423ffa.js";import"./iconBase-1.0.5-f30be7a9.js";import"./Close-1.0.5-697e5a3b.js";const B=({columnName:s,columnValue:p,columnMetaData:t,storeColumn:x,columnValidation:r,onColumnChange:y,metaData:h,storeForm:g,enumValues:E,formMode:b})=>{T.debug(s,p,t,x,r,h,g,E,b);const R={className:x.classNames,readOnly:b===v.VIEW||b===v.UPDATE&&h.primary_key.includes(s)},F=r!=null&&r.error?r==null?void 0:r.text:"Select from list",j=()=>H.OUTLINED,f=null;return e.jsxs(_,{children:[e.jsx(U,{variant:j(),children:t.formLabel}),e.jsx(k,{error:r==null?void 0:r.error,label:t.formLabel,value:p??"",inputProps:R,variant:j(),onChange:L=>{y(s,L.target.value===""?null:L.target.value)},children:(()=>{const L=E.map(function(A){return e.jsx(I,{"data-id":s,value:A,children:A},A)});return t.is_nullable==="YES"?[e.jsx(I,{"data-id":"pp-empty-enum-item",value:f,children:" "},"pp-empty-enum-item")].concat(L):L})()}),e.jsx(d,{children:S(x,F)})]})},G=({columnName:s,columnValue:p,columnMetaData:t,storeColumn:x,columnValidation:r,onColumnChange:y,metaData:h,enumValues:g,formMode:E,orientation:b})=>{T.debug(s,p,t,x,r,h,g,E,b);const R=r!=null&&r.error?r==null?void 0:r.text:"Select from radio group";return e.jsxs(_,{children:[e.jsx(w,{children:t.formLabel}),e.jsx(W,{className:x.classNames,value:p,sx:{flexDirection:b===O.RADIO_HORIZONTAL?"row":"column",paddingLeft:"12px"},onChange:F=>{y(s,F.target.value)},children:(()=>g.map(function(j){return e.jsx(Z,{control:e.jsx($,{disabled:E===v.VIEW||E===v.UPDATE&&h.primary_key.includes(s)}),value:j,label:j},j)}))()}),e.jsx(d,{children:S(x,R)})]})},D=({appId:s,columnName:p,columnValue:t,columnInitialValue:x,columnMetaData:r,storeColumn:y,columnValidation:h,onColumnChange:g,metaData:E,context:b,storeTable:R,storeForm:F,formMode:j})=>{const f=i(P.getState(),s,p);T.debug(f,s,p,t);const L=r.column_type.replace("enum(","").replace(")","").replaceAll("'","").split(",");switch(f==null?void 0:f.updatableEnum){case O.RADIO_HORIZONTAL:case O.RADIO_VERTICAL:return e.jsx(G,{appId:s,columnName:p,columnValue:t,columnInitialValue:x,columnMetaData:r,storeColumn:y,onColumnChange:g,metaData:E,context:b,storeTable:R,storeForm:F,enumValues:L,orientation:f==null?void 0:f.updatableEnum,formMode:j,columnValidation:h});default:return e.jsx(B,{appId:s,columnName:p,columnValue:t,columnInitialValue:x,columnMetaData:r,storeColumn:y,onColumnChange:g,metaData:E,context:b,storeTable:R,storeForm:F,enumValues:L,formMode:j,columnValidation:h})}};export{D as default};