<?php


namespace App\Domain\Behaviors\HeroRace;


class OrcBehavior extends HeroRaceBehavior
{

    public function getIconSVG(): string
    {
        return "<svg viewBox=\"-2 0 37.6 47\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\" style=\"display: block\">
                    <title>Orc Icon</title>
                    <path d=\"M16.637 4.60902C8.55684 4.87477 -2.78771 8.2293 0.622343 24.6478V31.5847C2.32989 35.4385 3.4 35.9412 7.11102 43.66C7.90787 44.859 11.8922 47 17.3563 47C22.8204 47 26.775 45.6747 27.625 43.66C30.8816 35.9412 31.6996 35.4385 33.4071 31.5847V24.6478C36.8223 8.20476 24.7193 4.86594 16.637 4.60902Z\" fill=\"#737964\"/>
                    <path d=\"M9.92859 31.3334C9.35836 32.5551 8.7088 35.9822 10.6724 39.9173L13.35 40.1102C12.5814 38.9207 10.8211 35.5 9.92859 31.3334Z\" fill=\"#E9E6E6\"/>
                    <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M15.8576 12.9019L11.9 4.00742e-07H14.185L19.8151 0H22.1001L18.1426 12.9019L18.1425 12.9015V12.902H15.8576V12.9019Z\" fill=\"#414C43\"/>
                    <path d=\"M24.6714 31.3334C25.2416 32.5551 25.8912 35.9822 23.9276 39.9173L21.25 40.1102C22.0186 38.9207 23.7788 35.5 24.6714 31.3334Z\" fill=\"#E9E6E6\"/>
                </svg>";
    }
}
