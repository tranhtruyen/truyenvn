<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <title>Opps! Lỗi rồi...</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="min-h-screen w-full bg-background text-white">
        <div class="mx-auto flex h-full min-h-screen max-w-[1200px] flex-col items-center justify-center">
            <Section style="h-fit w-full space-y-4">
                <h1 class="px-6 text-center font-secondary text-4xl md:text-6xl lg:px-0">
                    Oops! Chào user-kun, có vẻ bạn đã đi lạc vào vùng đất
                    <span class="text-primary"> 404</span>
                </h1>
                <h2 class="px-6 text-center font-secondary text-3xl md:text-5xl lg:px-0">
                    Đừng lo! Hãy để Zoro dẫn bạn quay lại vùng đất cũ.
                </h2>
            </Section>

            <div class="flex min-h-[400px] w-full flex-col items-center justify-center gap-4" style="height:500px; min-height:400px">
                <h3 id="text-hover" class="animate__fadeInUp animate__animated animate__faster text-2xl">
                    Follow me!
                </h3>

                <figure class="relative w-[250px]" style="height: 60%">
                    <img class="absolute rounded-2xl object-cover object-center w-full h-full" alt="zoro-img" src="/zoro.png" />
                </figure>

                <a href="/" id="button-hover" class="mt-4 rounded-xl border-2 border-white transition-all hover:border-none hover:bg-primary hover:text-white" style="border: 2px solid #fff; padding: 1.5rem;">
                    Theo Zoro về vùng đất NQT Comics
                </a>
            </div>
        </div>
    </div>
    <script>
        const buttonHover = document.getElementById('button-hover');
        const textHover = document.getElementById('text-hover');
        const isHover = false;

        buttonHover.addEventListener('mouseover', () => {
            textHover.innerHTML = 'Trust me...';
        });

        buttonHover.addEventListener('mouseout', () => {
            textHover.innerHTML = 'Follow me!';
        });
    </script>
</body>
</html>
