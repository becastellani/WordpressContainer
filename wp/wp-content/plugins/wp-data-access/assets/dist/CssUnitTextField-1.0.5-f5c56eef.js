import{l as s,j as o}from"./main-1.0.5.js";import{B as a,aq as p,ar as u}from"./main-1.0.5-a7d73e33.js";import{T as m}from"./TextField-1.0.5-7ecf712b.js";import{B as d}from"./AdminTheme-1.0.5-04bcac94.js";const h=({label:n,textField:r,defaultValue:e,updateSettings:x})=>{var t;return s.debug(n,r,e),o.jsx(m,{type:"text",label:n,value:r.cssValue!==void 0?r.cssValue+(r.cssUnit??""):"",disabled:!0,InputLabelProps:{shrink:!0},InputProps:{placeholder:r.cssValue!==void 0?((t=e.cssValue)==null?void 0:t.toString())+(e.cssUnit??""):"",endAdornment:o.jsxs(a,{sx:{display:"grid",gap:0},children:[o.jsx(d,{sx:{padding:0,"&.MuiButtonBase-root":{fontSize:"16px",width:"16px",minWidth:"16px",borderBottomRightRadius:0,borderBottomLeftRadius:0}},variant:"contained",color:"secondary",disabled:!0,disableRipple:!0,tabIndex:-1,onClick:()=>{},children:o.jsx(p,{})}),o.jsx(d,{sx:{padding:0,"&.MuiButtonBase-root":{fontSize:"16px",width:"16px",minWidth:"16px",borderTopLeftRadius:0,borderTopRightRadius:0}},variant:"contained",color:"secondary",disabled:!0,disableRipple:!0,tabIndex:-1,onClick:()=>{},children:o.jsx(u,{})})]})},onBlur:i=>{s.debug(i)},onChange:i=>{s.debug(i)},onKeyUp:i=>{s.debug(i)}})};export{h as C};