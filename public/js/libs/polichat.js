window.onload = () => {
    setTimeout(() => {

        (function(p,o,l,i,c,h,a,t){
            p['PolichatObject'] = c;p[c]=p[c]||function(){
            (p[c].q=p[c].q||[]).push(arguments);},h=o.createElement(l);p[c].t=p[c].t||new Date();
            a=o.getElementsByTagName(l)[0];h.async=1;h.src=i;a.parentNode.insertBefore(h,a);
        })(window,document,'script','https://ms.poli.digital/tags/public/services/serv.js','poli');
        
        poli('create', 46311);
        poli('send','webchat', {uid: '50871@polichat.webchat'});
        
    }, 5000);
};