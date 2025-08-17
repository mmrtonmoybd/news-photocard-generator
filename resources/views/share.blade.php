<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $card->title }}</title>

  <!-- Canonical -->
  <link rel="canonical" href="{{ route('social.share', [$card->hash]) }}" />

  <!-- Open Graph (Facebook & others) -->
  <meta property="og:type" content="article">
  <meta property="og:title" content="{{ $card->title }}">
  <meta property="og:image" content="{{ asset($card->generated_image) }}">
  <meta property="og:url" content="{{ route('social.share', [$card->hash]) }}">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="{{ $card->title }}">
  <meta name="twitter:title" content="{{ $card->title }}">
  <meta name="twitter:image" content="{{ asset($card->generated_image) }}">

  <!-- WordPress SEO-like Tags -->
  <meta name="robots" content="index, follow">
  <meta name="title" content="{{ $card->title }}">
  <meta name="image" content="{{ asset($card->generated_image) }}>

  <!-- Favicon -->
  <link rel="icon" href="/favicon.ico" type="image/x-icon">

  <!-- Styles -->
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background: #f9fafb;
      color: #111;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 1rem;
    }
    .news-card {
      max-width: 800px;
      width: 100%;
      background: #fff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .news-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.12);
    }
    .news-card img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: cover;
    }
    .news-card h1 {
      font-size: clamp(1.5rem, 3vw, 2.5rem);
      font-weight: 700;
      text-align: center;
      padding: 1.5rem;
      line-height: 1.3;
      background: linear-gradient(90deg, #111, #444);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    @media (max-width: 500px) {
      .news-card h1 {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>

  <article class="news-card">
    <img src="{{ asset($card->generated_image) }}" alt="{{ $card->title }}" loading="lazy">
    <h1>{{ $card->title }}</h1>
  </article>

</body>
</html>
