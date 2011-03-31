function focus() {
  if(document.forms && document.forms.length > 0 && document.forms[0].elements)  {
     for(i = 0; i < document.forms[0].elements.length; i++)
       if(document.forms[0].elements[i].type != "hidden") {
         document.forms[0].elements[i].focus();
         return;
       }
  }
}
