<html lang="en" class="light" style="color-scheme: light;">
	<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logoo.png') }}" type="image/png"/>
    <link rel="apple-touch-icon" href="{{ asset('logoo.png') }}"/>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="WebMotion HQ Logistics - Advanced shipping and logistics management system for real-time tracking, fleet management, and delivery operations. Streamline your logistics with our comprehensive admin dashboard."/>
    <meta name="keywords" content="logistics software, shipping management, fleet tracking, delivery system, cargo management, WebMotion HQ, logistics dashboard"/>
    <meta name="author" content="WebMotion HQ"/>
    <meta name="robots" content="index, follow"/>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:title" content="WebMotion HQ Logistics - Admin Dashboard"/>
    <meta property="og:description" content="Advanced shipping and logistics management system for real-time tracking and delivery operations"/>
    <meta property="og:image" content="{{ asset('logoo.png') }}"/>
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:url" content="{{ url()->current() }}"/>
    <meta name="twitter:title" content="WebMotion HQ Logistics - Admin Dashboard"/>
    <meta name="twitter:description" content="Advanced shipping and logistics management system for real-time tracking and delivery operations"/>
    <meta name="twitter:image" content="{{ asset('logoo.png') }}"/>
    
    <!-- Preload fonts -->
    <link rel="preload" href="_next/static/media/e4af272ccee01ff0-s.p.woff2" as="font" crossorigin="" type="font/woff2"/>
    <link rel="stylesheet" href="{{asset('_next/static/css/5eee5e3e85546ba3.css')}}" data-precedence="next"/>
    
    <meta name="next-size-adjust" content=""/>
    
    <title>WebMotion HQ Logistics – Admin Dashboard</title>
		<style>
			.imageye-selected {
			outline: 2px solid black !important;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5) !important;
			}
		</style>
		<style type="text/css">
			.with-scroll-bars-hidden {
			overflow: hidden !important;
			padding-right: 0px !important;
			}
			body[data-scroll-locked] {
			overflow: hidden !important;
			overscroll-behavior: contain;
			position: relative !important;
			padding-left: 0px;
			padding-top: 0px;
			padding-right: 0px;
			margin-left:0;
			margin-top:0;
			margin-right: 0px !important;
			}
			.right-scroll-bar-position {
			right: 0px !important;
			}
			.width-before-scroll-bar {
			margin-right: 0px !important;
			}
			.right-scroll-bar-position .right-scroll-bar-position {
			right: 0 !important;
			}
			.width-before-scroll-bar .width-before-scroll-bar {
			margin-right: 0 !important;
			}
			body[data-scroll-locked] {
			--removed-body-scroll-bar-size: 0px;
			}
		</style>
	</head>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove scroll-lock
    document.body.removeAttribute('data-scroll-locked');
    document.body.style.pointerEvents = 'auto';
    
    // Fix dropdown positioning
    const profileMenu = document.querySelector('[role="menu"]');
    if (profileMenu) {
        profileMenu.style.display = 'none';
        const wrapper = profileMenu.closest('[data-radix-popper-content-wrapper]');
        if (wrapper) {
            wrapper.style.position = 'static';
        }
    }
    
    // Profile dropdown toggle
    const profileBtn = document.querySelector('button[aria-controls^="radix"]');
    if (profileBtn) {
        profileBtn.addEventListener('click', function() {
            const menu = document.querySelector('[role="menu"]');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        });
    }
    
    // Sidebar toggle
    const menuBtn = document.querySelector('.block.lg\\:hidden');
    const sidebar = document.querySelector('.fixed.inset-y-0');
    if (menuBtn) {
        menuBtn.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
    }
});
</script>
	<body class="__className_e8ce0c" data-scroll-locked="1" style="pointer-events: none;">
		<span data-radix-focus-guard="" tabindex="0" style="outline: none; opacity: 0; position: fixed; pointer-events: none;" data-aria-hidden="true" aria-hidden="true"></span>
		<div hidden="" data-aria-hidden="true" aria-hidden="true">
			<!--$--><!--/$-->
		</div>
		<script>requestAnimationFrame(function(){$RT=performance.now()});</script>
        <script src="{{asset('_next/static/chunks/webpack-a2510a8647e753fd.js')}}" id="_R_" async=""></script>
        <script>$RB=[];$RV=function(b){$RT=performance.now();for(var a=0;a<b.length;a+=2){var c=b[a],e=b[a+1];null!==e.parentNode&&e.parentNode.removeChild(e);var f=c.parentNode;if(f){var g=c.previousSibling,h=0;do{if(c&&8===c.nodeType){var d=c.data;if("/$"===d||"/&"===d)if(0===h)break;else h--;else"$"!==d&&"$?"!==d&&"$~"!==d&&"$!"!==d&&"&"!==d||h++}d=c.nextSibling;f.removeChild(c);c=d}while(c);for(;e.firstChild;)f.insertBefore(e.firstChild,c);g.data="$";g._reactRetry&&g._reactRetry()}}b.length=0};
			$RC=function(b,a){if(a=document.getElementById(a))(b=document.getElementById(b))?(b.previousSibling.data="$~",$RB.push(b,a),2===$RB.length&&(b="number"!==typeof $RT?0:$RT,a=performance.now(),setTimeout($RV.bind(null,$RB),2300>a&&2E3<a?2300-a:b+300-a))):a.parentNode.removeChild(a)};$RC("B:0","S:0")
		</script>
        <script>(self.__next_f=self.__next_f||[]).push([0])</script>
        <script>self.__next_f.push([1,"1:\"$Sreact.fragment\"\n2:I[7360,[\"2960\",\"static/chunks/2960-50c6bb298c0a116b.js\",\"4400\",\"static/chunks/4400-090f20fe363681d3.js\",\"2472\",\"static/chunks/2472-753e678bc42bc97a.js\",\"1998\",\"static/chunks/1998-d14cc1a79a229042.js\",\"328\",\"static/chunks/328-eebb9acd6b46eefa.js\",\"1702\",\"static/chunks/1702-7a4960c3fc60a993.js\",\"4356\",\"static/chunks/4356-c0183a6ae8173a1e.js\",\"7177\"],\"ThemeProvider\"]\n3:I[90936,[\"2960\",\"static/chunks/2960-50c6bb298c0a116b.js\",\"4400\",\"static/chunks/4400-090f20fe363681d3.js\",\"2472\",\"static/chunks/2472-753e678bc42bc97a.js\",\"1998\",\"static/chunks/1998-d14cc1a79a229042.js\",\"328\",\"static/chunks/328-eebb9acd6b46eefa.js\",\"1702\",\"static/chunks/1702-7a4960c3fc60a993.js\",\"4356\",\"static/chunks/4356-c0183a6ae8173a1e.js\",\"7177\",\"static/chunks/app/layout-cc9ba3c2a1346fa3.js\"],\"default\"]\n4:I[11423,[],\"\"]\n5:I[42969,[\"2960\",\"static/chunks/2960-50c6bb298c0a116b.js\",\"1702\",\"static/chunks/1702-7a4960c3fc60a993.js\",\"8039\",\"static/chunks/app/error-cf80389aefa2cd62.js\"],\"default\"]\n6:I[19235,[],\"\"]\n7:I[61702,[\"1702\",\"static/chunks/1702-7a4960c3fc60a993.js\",\"4345\",\"static/chunks/app/not-found-19d90919b18a0206.js\"],\"\"]\n10:I[47173,[],\"\"]\n:HL[\"/_next/static/media/e4af272ccee01ff0-s.p.woff2\",\"font\",{\"crossOrigin\":\"\",\"type\":\"font/woff2\"}]\n:HL[\"/_next/static/css/5eee5e3e85546ba3.css\",\"style\"]\n:HL[\"/_next/static/css/931b179cff3131dc.css\",\"style\"]\n"])</script><script>self.__next_f.push([1,"0:{\"P\":null,\"b\":\"lOUGRG5VelF99c2c6Qwn-\",\"p\":\"\",\"c\":[\"\",\"\"],\"i\":false,\"f\":[[[\"\",{\"children\":[\"__PAGE__\",{}]},\"$undefined\",\"$undefined\",true],[\"\",[\"$\",\"$1\",\"c\",{\"children\":[[[\"$\",\"link\",\"0\",{\"rel\":\"stylesheet\",\"href\":\"/_next/static/css/5eee5e3e85546ba3.css\",\"precedence\":\"next\",\"crossOrigin\":\"$undefined\",\"nonce\":\"$undefined\"}],[\"$\",\"link\",\"1\",{\"rel\":\"stylesheet\",\"href\":\"/_next/static/css/931b179cff3131dc.css\",\"precedence\":\"next\",\"crossOrigin\":\"$undefined\",\"nonce\":\"$undefined\"}]],[\"$\",\"html\",null,{\"lang\":\"en\",\"children\":[\"$\",\"body\",null,{\"className\":\"__className_e8ce0c\",\"children\":[\"$\",\"$L2\",null,{\"attribute\":\"class\",\"defaultTheme\":\"system\",\"enableSystem\":true,\"children\":[\"$\",\"div\",null,{\"className\":\"min-h-screen bg-background\",\"children\":[\"$\",\"$L3\",null,{\"children\":[\"$\",\"$L4\",null,{\"parallelRouterKey\":\"children\",\"error\":\"$5\",\"errorStyles\":[],\"errorScripts\":[],\"template\":[\"$\",\"$L6\",null,{}],\"templateStyles\":\"$undefined\",\"templateScripts\":\"$undefined\",\"notFound\":[[\"$\",\"div\",null,{\"className\":\"fixed inset-0 z-50 bg-background flex items-center justify-center p-4 \",\"children\":[[\"$\",\"div\",null,{\"className\":\"absolute inset-0 bg-gradient-to-br from-background via-background to-muted/20\"}],[\"$\",\"div\",null,{\"ref\":\"$undefined\",\"className\":\"rounded-lg border bg-card text-card-foreground relative w-full max-w-lg shadow-lg max-h-[90vh] overflow-y-auto\",\"children\":[[\"$\",\"div\",null,{\"ref\":\"$undefined\",\"className\":\"flex flex-col space-y-1.5 p-4 md:p-6 text-center\",\"children\":[[\"$\",\"div\",null,{\"className\":\"mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10\",\"children\":[\"$\",\"svg\",null,{\"ref\":\"$undefined\",\"xmlns\":\"http://www.w3.org/2000/svg\",\"width\":24,\"height\":24,\"viewBox\":\"0 0 24 24\",\"fill\":\"none\",\"stroke\":\"currentColor\",\"strokeWidth\":2,\"strokeLinecap\":\"round\",\"strokeLinejoin\":\"round\",\"className\":\"lucide lucide-file-question-mark h-8 w-8 text-primary\",\"aria-hidden\":\"true\",\"children\":[[\"$\",\"path\",\"p32p05\",{\"d\":\"M12 17h.01\"}],[\"$\",\"path\",\"1mlx9k\",{\"d\":\"M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z\"}],[\"$\",\"path\",\"mhlwft\",{\"d\":\"M9.1 9a3 3 0 0 1 5.82 1c0 2-3 3-3 3\"}],\"$undefined\"]}]}],[\"$\",\"h3\",null,{\"ref\":\"$undefined\",\"className\":\"sm:text-2xl tracking-tight text-2xl md:text-3xl font-bold\",\"children\":\"Page Not Found\"}],[\"$\",\"div\",null,{\"ref\":\"$undefined\",\"className\":\"text-muted-foreground text-lg\",\"children\":\"The page you're looking for doesn't exist or has been moved.\"}]]}],[\"$\",\"div\",null,{\"ref\":\"$undefined\",\"className\":\"p-4 md:p-6 pt-0 space-y-6\",\"children\":[[\"$\",\"div\",null,{\"className\":\"text-center\",\"children\":[[\"$\",\"div\",null,{\"className\":\"text-6xl font-bold text-muted-foreground/20 mb-2\",\"children\":\"404\"}],[\"$\",\"p\",null,{\"className\":\"text-sm text-muted-foreground\",\"children\":\"This might happen if you followed a broken link or typed the URL incorrectly.\"}]]}],[\"$\",\"div\",null,{\"className\":\"space-y-3\",\"children\":[[\"$\",\"$L7\",null,{\"href\":\"/\",\"children\":[[\"$\",\"svg\",null,{\"ref\":\"$undefined\",\"xmlns\":\"http://www.w3.org/2000/svg\",\"width\":24,\"height\":24,\"viewBox\":\"0 0 24 24\",\"fill\":\"none\",\"stroke\":\"currentColor\",\"strokeWidth\":2,\"strokeLinecap\":\"round\",\"strokeLinejoin\":\"round\",\"className\":\"lucide lucide-house mr-2 h-4 w-4\",\"aria-hidden\":\"true\",\"children\":[[\"$\",\"path\",\"5wwlr5\",{\"d\":\"M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8\"}],[\"$\",\"path\",\"1d0kgt\",{\"d\":\"M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z\"}],\"$undefined\"]}],\"Go to Dashboard\"],\"className\":\"inline-flex items-center justify-center whitespace-nowrap text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-11 rounded-md px-8 w-full\",\"ref\":null}],[\"$\",\"div\",null,{\"className\":\"grid grid-cols-2 gap-2\",\"children\":[[\"$\",\"$L7\",null,{\"href\":\"/shipments\",\"children\":[[\"$\",\"svg\",null,{\"ref\":\"$undefined\",\"xmlns\":\"http://www.w3.org/2000/svg\",\"width\":24,\"height\":24,\"viewBox\":\"0 0 24 24\",\"fill\":\"none\",\"stroke\":\"currentColor\",\"strokeWidth\":2,\"strokeLinecap\":\"round\",\"strokeLinejoin\":\"round\",\"className\":\"lucide lucide-truck mr-2 h-4 w-4\",\"aria-hidden\":\"true\",\"children\":[[\"$\",\"path\",\"wrbu53\",{\"d\":\"M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2\"}],[\"$\",\"path\",\"1lyqi6\",{\"d\":\"M15 18H9\"}],[\"$\",\"path\",\"lysw3i\",{\"d\":\"M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14\"}],\"$L8\",\"$L9\",\"$undefined\"]}],\"Shipments\"],\"className\":\"inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2\",\"ref\":null}],\"$La\"]}],\"$Lb\"]}],\"$Lc\"]}]]}]]}],[]],\"forbidden\":\"$undefined\",\"unauthorized\":\"$undefined\"}]}]}]}]}]}]]}],{\"children\":[\"__PAGE__\",\"$Ld\",{},null,false]},[\"$Le\",[],[]],false],\"$Lf\",false]],\"m\":\"$undefined\",\"G\":[\"$10\",[]],\"s\":false,\"S\":true}\n"])</script><script>self.__next_f.push([1,"11:I[63976,[\"2960\",\"static/chunks/2960-50c6bb298c0a116b.js\",\"4400\",\"static/chunks/4400-090f20fe363681d3.js\",\"2472\",\"static/chunks/2472-753e678bc42bc97a.js\",\"2081\",\"static/chunks/2081-8e49e9445c003ec8.js\",\"328\",\"static/chunks/328-eebb9acd6b46eefa.js\",\"1702\",\"static/chunks/1702-7a4960c3fc60a993.js\",\"1457\",\"static/chunks/1457-cb618682621f6fc5.js\",\"5662\",\"static/chunks/5662-904fc19b5bcb90dc.js\",\"5558\",\"static/chunks/5558-8e67ec4d42ccbbe0.js\",\"7218\",\"static/chunks/7218-501ae01ba83f602c.js\",\"8250\",\"static/chunks/8250-1db75e24fb09eead.js\",\"6932\",\"static/chunks/6932-c1a1fab8314d591e.js\",\"8974\",\"static/chunks/app/page-d0116d3baf0de987.js\"],\"default\"]\n12:I[68445,[],\"OutletBoundary\"]\n14:I[57867,[],\"AsyncMetadataOutlet\"]\n16:I[68445,[],\"ViewportBoundary\"]\n18:I[68445,[],\"MetadataBoundary\"]\n19:\"$Sreact.suspense\"\n8:[\"$\",\"circle\",\"332jqn\",{\"cx\":\"17\",\"cy\":\"18\",\"r\":\"2\"}]\n9:[\"$\",\"circle\",\"19iecd\",{\"cx\":\"7\",\"cy\":\"18\",\"r\":\"2\"}]\n"])</script><script>self.__next_f.push([1,"a:[\"$\",\"$L7\",null,{\"href\":\"/orders\",\"children\":[[\"$\",\"svg\",null,{\"ref\":\"$undefined\",\"xmlns\":\"http://www.w3.org/2000/svg\",\"width\":24,\"height\":24,\"viewBox\":\"0 0 24 24\",\"fill\":\"none\",\"stroke\":\"currentColor\",\"strokeWidth\":2,\"strokeLinecap\":\"round\",\"strokeLinejoin\":\"round\",\"className\":\"lucide lucide-package mr-2 h-4 w-4\",\"aria-hidden\":\"true\",\"children\":[[\"$\",\"path\",\"1a0edw\",{\"d\":\"M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z\"}],[\"$\",\"path\",\"d0xqtd\",{\"d\":\"M12 22V12\"}],[\"$\",\"polyline\",\"ousv84\",{\"points\":\"3.29 7 12 12 20.71 7\"}],[\"$\",\"path\",\"1c824w\",{\"d\":\"m7.5 4.27 9 5.15\"}],\"$undefined\"]}],\"Orders\"],\"className\":\"inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2\",\"ref\":null}]\n"])</script><script>self.__next_f.push([1,"b:[\"$\",\"div\",null,{\"className\":\"grid grid-cols-2 gap-2\",\"children\":[[\"$\",\"$L7\",null,{\"href\":\"/fleet\",\"children\":[[\"$\",\"svg\",null,{\"ref\":\"$undefined\",\"xmlns\":\"http://www.w3.org/2000/svg\",\"width\":24,\"height\":24,\"viewBox\":\"0 0 24 24\",\"fill\":\"none\",\"stroke\":\"currentColor\",\"strokeWidth\":2,\"strokeLinecap\":\"round\",\"strokeLinejoin\":\"round\",\"className\":\"lucide lucide-users mr-2 h-4 w-4\",\"aria-hidden\":\"true\",\"children\":[[\"$\",\"path\",\"1yyitq\",{\"d\":\"M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2\"}],[\"$\",\"path\",\"16gr8j\",{\"d\":\"M16 3.128a4 4 0 0 1 0 7.744\"}],[\"$\",\"path\",\"kshegd\",{\"d\":\"M22 21v-2a4 4 0 0 0-3-3.87\"}],[\"$\",\"circle\",\"nufk8\",{\"cx\":\"9\",\"cy\":\"7\",\"r\":\"4\"}],\"$undefined\"]}],\"Fleet\"],\"className\":\"inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2\",\"ref\":null}],[\"$\",\"$L7\",null,{\"href\":\"/reports\",\"children\":[[\"$\",\"svg\",null,{\"ref\":\"$undefined\",\"xmlns\":\"http://www.w3.org/2000/svg\",\"width\":24,\"height\":24,\"viewBox\":\"0 0 24 24\",\"fill\":\"none\",\"stroke\":\"currentColor\",\"strokeWidth\":2,\"strokeLinecap\":\"round\",\"strokeLinejoin\":\"round\",\"className\":\"lucide lucide-chart-column mr-2 h-4 w-4\",\"aria-hidden\":\"true\",\"children\":[[\"$\",\"path\",\"c24i48\",{\"d\":\"M3 3v16a2 2 0 0 0 2 2h16\"}],[\"$\",\"path\",\"2bz60n\",{\"d\":\"M18 17V9\"}],[\"$\",\"path\",\"1frdt8\",{\"d\":\"M13 17V5\"}],[\"$\",\"path\",\"17ska0\",{\"d\":\"M8 17v-3\"}],\"$undefined\"]}],\"Reports\"],\"className\":\"inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2\",\"ref\":null}]]}]\n"])</script><script>self.__next_f.push([1,"c:[\"$\",\"div\",null,{\"className\":\"text-center pt-4 border-t\",\"children\":[[\"$\",\"p\",null,{\"className\":\"text-xs text-muted-foreground mb-2\",\"children\":\"CargoMax Logistics Platform\"}],[\"$\",\"p\",null,{\"className\":\"text-xs text-muted-foreground\",\"children\":[\"Need help?\",\" \",[\"$\",\"$L7\",null,{\"href\":\"/help\",\"className\":\"text-primary hover:underline font-medium\",\"children\":\"Contact Support\"}]]}]]}]\nd:[\"$\",\"$1\",\"c\",{\"children\":[[\"$\",\"div\",null,{\"children\":[\"$\",\"$L11\",null,{}]}],null,[\"$\",\"$L12\",null,{\"children\":[\"$L13\",[\"$\",\"$L14\",null,{\"promise\":\"$@15\"}]]}]]}]\ne:[\"$\",\"div\",\"l\",{\"className\":\"flex items-center justify-center h-screen\",\"children\":[\"$\",\"p\",null,{\"className\":\"text-xl text-green-500\",\"children\":\"Loading...\"}]}]\nf:[\"$\",\"$1\",\"h\",{\"children\":[null,[[\"$\",\"$L16\",null,{\"children\":\"$L17\"}],[\"$\",\"meta\",null,{\"name\":\"next-size-adjust\",\"content\":\"\"}]],[\"$\",\"$L18\",null,{\"children\":[\"$\",\"div\",null,{\"hidden\":true,\"children\":[\"$\",\"$19\",null,{\"fallback\":null,\"children\":\"$L1a\"}]}]}]]}]\n"])</script><script>self.__next_f.push([1,"17:[[\"$\",\"meta\",\"0\",{\"charSet\":\"utf-8\"}],[\"$\",\"meta\",\"1\",{\"name\":\"viewport\",\"content\":\"width=device-width, initial-scale=1\"}]]\n13:null\n"])</script><script>self.__next_f.push([1,"1b:I[39283,[],\"IconMark\"]\n"])</script><script>self.__next_f.push([1,"15:{\"metadata\":[[\"$\",\"title\",\"0\",{\"children\":\"Cargomax – Shipping \u0026 Logistics Admin Dashboard Next.js Template\"}],[\"$\",\"meta\",\"1\",{\"name\":\"description\",\"content\":\"Cargomax is a modern and responsive Shipping \u0026 Logistics Admin Dashboard Template designed for cargo management, freight tracking, warehouse control, and logistics operations. Built with clean UI components and advanced features, Cargomax offers real-time shipment monitoring, driver assignments, order tracking, fleet status, and warehouse insights. Ideal for logistics companies, transportation services, and supply chain dashboards, this template ensures a seamless and intuitive admin experience. Fully responsive, customizable, and developer-friendly – boost your logistics platform with Cargomax today.\"}],[\"$\",\"link\",\"2\",{\"rel\":\"icon\",\"href\":\"/favicon.ico\",\"type\":\"image/x-icon\",\"sizes\":\"512x512\"}],[\"$\",\"$L1b\",\"3\",{}]],\"error\":null,\"digest\":\"$undefined\"}\n"])</script><script>self.__next_f.push([1,"1a:\"$15:metadata\"\n"])</script>
		<next-route-announcer style="position: absolute;" data-aria-hidden="true" aria-hidden="true"></next-route-announcer>
		<script nonce="">((e,t,a,h,d,r,l,s)=>{let c=document.documentElement,y=["light","dark"];function i(t){var a;(Array.isArray(e)?e:[e]).forEach(e=>{let a="class"===e,h=a&&r?d.map(e=>r[e]||e):d;a?(c.classList.remove(...h),c.classList.add(r&&r[t]?r[t]:t)):c.setAttribute(e,t)}),a=t,s&&y.includes(a)&&(c.style.colorScheme=a)}if(h)i(h);else try{let e=localStorage.getItem(t)||a,h=l&&"system"===e?window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light":e;i(h)}catch(e){}})("class","theme","system",null,["light","dark"],null,true,true)</script>
		<div class="min-h-screen bg-background">


			 @include('admin.body.sidebar')


			<div class="flex flex-1 flex-col ml-0 lg:ml-64">
				

                @include('admin.body.header')


				

                  @yield('admin')


			</div>
		</div>
		<span id="recharts_measurement_span" aria-hidden="true" style="position: absolute; top: -20000px; left: 0px; padding: 0px; margin: 0px; border: none; white-space: pre; font-size: 16px; letter-spacing: normal;" data-aria-hidden="true">0</span>

		
	
        <span data-radix-focus-guard="" tabindex="0" style="outline: none; opacity: 0; position: fixed; pointer-events: none;" data-aria-hidden="true" aria-hidden="true"></span>
	<!-- Add this just before </body> tag in your admin_dashboard.blade.php -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ===================================
    // FIX SCROLL ISSUE
    // ===================================
    document.body.removeAttribute('data-scroll-locked');
    document.body.style.removeProperty('pointer-events');
    document.body.classList.remove('with-scroll-bars-hidden');
    
    document.querySelectorAll('[data-aria-hidden]').forEach(el => {
        el.removeAttribute('data-aria-hidden');
        el.removeAttribute('aria-hidden');
    });

    
    // ===================================
    // SIDEBAR TOGGLE (Mobile)
    // ===================================
    const sidebar = document.querySelector('aside.fixed.inset-y-0, .fixed.inset-y-0, nav.fixed');
    const menuBtn = document.querySelector('button.block.lg\\:hidden, header button:has(.lucide-menu)');
    
    if (menuBtn && sidebar) {
        // Fix menu button display
        if (!menuBtn.classList.contains('inline-flex')) {
            menuBtn.classList.add('inline-flex');
        }
        
        menuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            sidebar.classList.toggle('-translate-x-full');
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            menuBtn.setAttribute('aria-expanded', isOpen);
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(e.target) && !menuBtn.contains(e.target)) {
                    if (!sidebar.classList.contains('-translate-x-full')) {
                        sidebar.classList.add('-translate-x-full');
                        menuBtn.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                menuBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    
    // ===================================
    // DROPDOWN MENUS - COMPREHENSIVE FIX
    // ===================================
    
    // Function to close all dropdowns
    function closeAllDropdowns() {
        // Close existing dropdowns
        document.querySelectorAll('[role="menu"]').forEach(menu => {
            menu.style.display = 'none';
            const wrapper = menu.closest('[data-radix-popper-content-wrapper]');
            if (wrapper) {
                wrapper.style.display = 'none';
            }
        });
        
        // Reset all button states
        document.querySelectorAll('button[aria-haspopup="menu"]').forEach(btn => {
            btn.setAttribute('aria-expanded', 'false');
            btn.setAttribute('data-state', 'closed');
        });
    }
    
    // Function to position dropdown relative to button
    function positionDropdown(button, wrapper) {
        const rect = button.getBoundingClientRect();
        wrapper.style.position = 'fixed';
        wrapper.style.left = '0px';
        wrapper.style.top = '0px';
        wrapper.style.transform = `translate(${rect.right - 200}px, ${rect.bottom + 8}px)`;
        wrapper.style.zIndex = '9999';
        wrapper.style.minWidth = 'max-content';
    }
    
    // PROFILE DROPDOWN
    const profileBtn = document.querySelector('button#radix-_r_4_');
    let profileMenuWrapper = document.querySelector('[data-radix-popper-content-wrapper]:has(#radix-_r_5_)');
    let profileMenu = document.querySelector('#radix-_r_5_');
    
    if (profileBtn) {
        console.log('✅ Profile button found');
        
        // Initially hide the menu
        if (profileMenuWrapper) {
            profileMenuWrapper.style.display = 'none';
        }
        if (profileMenu) {
            profileMenu.style.display = 'none';
        }
        
        profileBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Profile button clicked');
            
            const isOpen = profileBtn.getAttribute('aria-expanded') === 'true';
            
            // Close all other dropdowns
            closeAllDropdowns();
            
            if (!isOpen) {
                // Open this dropdown
                if (profileMenuWrapper && profileMenu) {
                    profileMenuWrapper.style.display = 'block';
                    profileMenu.style.display = 'block';
                    positionDropdown(profileBtn, profileMenuWrapper);
                    profileBtn.setAttribute('aria-expanded', 'true');
                    profileBtn.setAttribute('data-state', 'open');
                    console.log('Profile menu opened');
                }
            }
        });
    } else {
        console.warn('❌ Profile button not found');
    }
    
    // NOTIFICATION DROPDOWN
    const notificationBtn = document.querySelector('button#radix-_r_0_');
    
    if (notificationBtn) {
        console.log('✅ Notification button found');
        
        // Check if notification menu exists
        let notificationMenuWrapper = document.querySelector('[data-radix-popper-content-wrapper]:has([aria-labelledby="radix-_r_0_"])');
        let notificationMenu = document.querySelector('[role="menu"][aria-labelledby="radix-_r_0_"]');
        
        // If menu doesn't exist, create it
        if (!notificationMenu) {
            console.log('Creating notification menu...');
            
            notificationMenuWrapper = document.createElement('div');
            notificationMenuWrapper.setAttribute('data-radix-popper-content-wrapper', '');
            notificationMenuWrapper.setAttribute('dir', 'ltr');
            notificationMenuWrapper.style.display = 'none';
            
            notificationMenu = document.createElement('div');
            notificationMenu.setAttribute('role', 'menu');
            notificationMenu.setAttribute('id', 'notification-menu');
            notificationMenu.setAttribute('aria-labelledby', 'radix-_r_0_');
            notificationMenu.setAttribute('data-side', 'bottom');
            notificationMenu.setAttribute('data-align', 'end');
            notificationMenu.setAttribute('tabindex', '-1');
            notificationMenu.className = 'z-50 min-w-[320px] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md';
            
            notificationMenu.innerHTML = `
                <div class="px-2 py-1.5 text-sm font-semibold">Notifications</div>
                <div role="separator" class="-mx-1 my-1 h-px bg-muted"></div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <div class="flex-1">
                        <p class="font-medium">New shipment arrived</p>
                        <p class="text-xs text-muted-foreground">2 minutes ago</p>
                    </div>
                </div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <div class="flex-1">
                        <p class="font-medium">Driver assignment updated</p>
                        <p class="text-xs text-muted-foreground">1 hour ago</p>
                    </div>
                </div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <div class="flex-1">
                        <p class="font-medium">Order #1234 delivered</p>
                        <p class="text-xs text-muted-foreground">3 hours ago</p>
                    </div>
                </div>
                <div role="separator" class="-mx-1 my-1 h-px bg-muted"></div>
                <div role="menuitem" class="relative flex select-none items-center justify-center rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer text-primary" tabindex="-1">
                    View all notifications
                </div>
            `;
            
            notificationMenuWrapper.appendChild(notificationMenu);
            document.body.appendChild(notificationMenuWrapper);
        } else {
            notificationMenuWrapper.style.display = 'none';
        }
        
        notificationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Notification button clicked');
            
            const isOpen = notificationBtn.getAttribute('aria-expanded') === 'true';
            
            closeAllDropdowns();
            
            if (!isOpen) {
                notificationMenuWrapper.style.display = 'block';
                notificationMenu.style.display = 'block';
                positionDropdown(notificationBtn, notificationMenuWrapper);
                notificationBtn.setAttribute('aria-expanded', 'true');
                notificationBtn.setAttribute('data-state', 'open');
                console.log('Notification menu opened');
            }
        });
    } else {
        console.warn('❌ Notification button not found');
    }
    
    // LANGUAGE DROPDOWN
    const languageBtn = document.querySelector('button#radix-_r_2_');
    
    if (languageBtn) {
        console.log('✅ Language button found');
        
        let languageMenuWrapper = document.querySelector('[data-radix-popper-content-wrapper]:has([aria-labelledby="radix-_r_2_"])');
        let languageMenu = document.querySelector('[role="menu"][aria-labelledby="radix-_r_2_"]');
        
        if (!languageMenu) {
            console.log('Creating language menu...');
            
            languageMenuWrapper = document.createElement('div');
            languageMenuWrapper.setAttribute('data-radix-popper-content-wrapper', '');
            languageMenuWrapper.setAttribute('dir', 'ltr');
            languageMenuWrapper.style.display = 'none';
            
            languageMenu = document.createElement('div');
            languageMenu.setAttribute('role', 'menu');
            languageMenu.setAttribute('id', 'language-menu');
            languageMenu.setAttribute('aria-labelledby', 'radix-_r_2_');
            languageMenu.setAttribute('data-side', 'bottom');
            languageMenu.setAttribute('data-align', 'end');
            languageMenu.setAttribute('tabindex', '-1');
            languageMenu.className = 'z-50 min-w-[180px] overflow-hidden rounded-md border bg-popover p-1 text-popover-foreground shadow-md';
            
            languageMenu.innerHTML = `
                <div class="px-2 py-1.5 text-sm font-semibold">Language</div>
                <div role="separator" class="-mx-1 my-1 h-px bg-muted"></div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <span>🇺🇸</span>
                    <span>English</span>
                </div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <span>🇪🇸</span>
                    <span>Español</span>
                </div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <span>🇫🇷</span>
                    <span>Français</span>
                </div>
                <div role="menuitem" class="relative flex select-none items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none transition-colors hover:bg-accent cursor-pointer" tabindex="-1">
                    <span>🇩🇪</span>
                    <span>Deutsch</span>
                </div>
            `;
            
            languageMenuWrapper.appendChild(languageMenu);
            document.body.appendChild(languageMenuWrapper);
        } else {
            languageMenuWrapper.style.display = 'none';
        }
        
        languageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Language button clicked');
            
            const isOpen = languageBtn.getAttribute('aria-expanded') === 'true';
            
            closeAllDropdowns();
            
            if (!isOpen) {
                languageMenuWrapper.style.display = 'block';
                languageMenu.style.display = 'block';
                positionDropdown(languageBtn, languageMenuWrapper);
                languageBtn.setAttribute('aria-expanded', 'true');
                languageBtn.setAttribute('data-state', 'open');
                console.log('Language menu opened');
            }
        });
    } else {
        console.warn('❌ Language button not found');
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[role="menu"]') && 
            !e.target.closest('[data-radix-popper-content-wrapper]') &&
            !e.target.closest('button[aria-haspopup="menu"]')) {
            closeAllDropdowns();
        }
    });
    
    // Close dropdown when pressing Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
        }
    });
    
    // Reposition dropdowns on scroll/resize
    let currentOpenBtn = null;
    let currentOpenWrapper = null;
    
    function trackOpenDropdown() {
        const openBtn = document.querySelector('button[aria-expanded="true"][aria-haspopup="menu"]');
        if (openBtn) {
            currentOpenBtn = openBtn;
            currentOpenWrapper = document.querySelector('[data-radix-popper-content-wrapper]:not([style*="display: none"])');
        }
    }
    
    window.addEventListener('scroll', function() {
        trackOpenDropdown();
        if (currentOpenBtn && currentOpenWrapper && currentOpenWrapper.style.display !== 'none') {
            positionDropdown(currentOpenBtn, currentOpenWrapper);
        }
    });
    
    window.addEventListener('resize', function() {
        closeAllDropdowns();
    });

    
    // ===================================
    // THEME TOGGLE
    // ===================================
    const themeToggle = document.querySelector('button[aria-label*="theme"]');
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const html = document.documentElement;
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                html.style.colorScheme = 'light';
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                html.style.colorScheme = 'dark';
                localStorage.setItem('theme', 'dark');
            }
        });
    }
    
    
    console.log('✅ All functionality initialized');
    console.log('Profile button:', profileBtn ? 'Found' : 'Not found');
    console.log('Notification button:', notificationBtn ? 'Found' : 'Not found');
    console.log('Language button:', languageBtn ? 'Found' : 'Not found');
});
</script>


<script>
// Debug and fix sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== SIDEBAR DEBUG ===');
    
    // Find all possible sidebar elements
    const possibleSidebars = [
        document.querySelector('aside'),
        document.querySelector('nav.fixed'),
        document.querySelector('.fixed.inset-y-0'),
        document.querySelector('[class*="sidebar"]'),
        ...document.querySelectorAll('.fixed')
    ].filter(el => el !== null);
    
    console.log('Possible sidebar elements found:', possibleSidebars);
    
    // Find all possible menu buttons
    const possibleMenuButtons = [
        document.querySelector('button.block.lg\\:hidden'),
        document.querySelector('header button:has(.lucide-menu)'),
        document.querySelector('button:has(svg[class*="menu"])'),
        ...document.querySelectorAll('header button')
    ].filter(el => el !== null);
    
    console.log('Possible menu buttons found:', possibleMenuButtons);
    
    // Try to identify the correct elements
    let sidebar = possibleSidebars.find(el => {
        const classes = el.className;
        return classes.includes('fixed') && 
               (classes.includes('inset-y') || classes.includes('left-0') || classes.includes('sidebar'));
    });
    
    let menuBtn = possibleMenuButtons.find(el => {
        return el.querySelector('.lucide-menu') || 
               el.querySelector('svg[class*="menu"]') ||
               el.className.includes('lg:hidden');
    });
    
    console.log('Selected sidebar:', sidebar);
    console.log('Selected menu button:', menuBtn);
    
    if (!sidebar) {
        console.error('❌ Sidebar not found! Make sure your sidebar has class "fixed" and "inset-y-0"');
        return;
    }
    
    if (!menuBtn) {
        console.error('❌ Menu button not found! Make sure your menu button has class "block lg:hidden"');
        return;
    }
    
    // Ensure sidebar starts hidden on mobile
    if (window.innerWidth < 1024 && !sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.add('-translate-x-full');
    }
    
    // Add toggle functionality
    menuBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const wasHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full');
        
        // Update button state
        menuBtn.setAttribute('aria-expanded', wasHidden ? 'true' : 'false');
        
        console.log('✅ Sidebar toggled:', wasHidden ? 'OPEN' : 'CLOSED');
        
        // Add backdrop on mobile when open
        if (wasHidden && window.innerWidth < 1024) {
            const backdrop = document.createElement('div');
            backdrop.id = 'sidebar-backdrop';
            backdrop.className = 'fixed inset-0 bg-black/50 z-30 lg:hidden';
            backdrop.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 30;';
            
            backdrop.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                menuBtn.setAttribute('aria-expanded', 'false');
                backdrop.remove();
            });
            
            document.body.appendChild(backdrop);
        } else {
            const backdrop = document.getElementById('sidebar-backdrop');
            if (backdrop) backdrop.remove();
        }
    });
    
    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth < 1024) {
            if (!sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                menuBtn.setAttribute('aria-expanded', 'false');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (backdrop) backdrop.remove();
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (backdrop) backdrop.remove();
        } else if (!sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
        }
    });
    
    console.log('✅ Sidebar toggle initialized successfully');
});
</script>








<style>
/* Additional CSS fixes for scrolling and dropdown positioning */

/* Ensure body can scroll */
body {
    overflow-y: auto !important;
    pointer-events: auto !important;
    position: relative !important;
}

/* Fix main content scrolling */
main {
    overflow-y: auto !important;
}

/* Ensure sidebar can scroll */
.fixed.inset-y-0 {
    overflow-y: auto !important;
}




/* Fix dropdown menu positioning */



/* Ensure dropdowns appear above everything */
[data-radix-popper-content-wrapper] {
    position: fixed !important;
    z-index: 9999 !important;
}

[role="menu"] {
    pointer-events: auto !important;
}

/* Make sure buttons are clickable */
button[aria-haspopup="menu"] {
    cursor: pointer !important;
    pointer-events: auto !important;
}


/* Ensure dropdowns appear above other content */
.header-right {
    position: relative;
}

.header-right > * {
    position: relative;
}

/* Remove any scroll locks */
.with-scroll-bars-hidden {
    overflow: auto !important;
    padding-right: 0 !important;
}

/* Smooth transitions */
.fixed.inset-y-0 {
    transition: transform 0.3s ease-in-out;
}
</style>
    </body>
</html>