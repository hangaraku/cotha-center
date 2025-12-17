/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
    './app/Livewire/**/*.php',
    './app/Filament/**/*.php',
    './resources/views/livewire/**/*.blade.php',
    // add any other relevant paths
  ],
  theme: {
    extend: {
      backgroundImage: {
        'hero-guitar': "url('/images/guitar.jpeg')",
        'blues-guitar': "url('/images/blues_guitar.jpeg')",
        'blues-guitar': "url('/images/blues_guitar.jpeg')",
        'jazz-guitar': "url('/images/jazz_guitar.jpeg')",
        'ukulele-guitar': "url('/images/ukulele_guitar.webp')",
        'folk-guitar': "url('/images/folk_guitar.jpeg')",
      },
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem',
        '6xl': '3rem',
        '7xl': '3.5rem',
        '8xl': '4rem',
        '9xl': '4.5rem',
        '10xl': '5rem',
        '11xl': '5.5rem',
        '12xl': '6rem',
        '14xl': '7rem',
        '16xl': '8rem',
        '20xl': '10rem',
      },
      colors: {
        'primary': '#cb5283',
        'accent-orange': '#9cd3eb'
      },
    },
  },
  plugins: [
    require('@tailwindcss/line-clamp'),
  ],
}

