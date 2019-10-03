<?php


namespace App\Domain\Behaviors\HeroRace;


class DwarfBehavior extends HeroRaceBehavior
{

    public function getIconSVG(): string
    {
        return "<svg viewBox=\"-1 0 39.2 49\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\" style=\"display: block\">
                    <title>Dwarf Icon</title>
                    <path d=\"M17.1811 0.00107729C9.78179 0.245562 -0.606866 3.33167 2.51586 18.4364V24.8182C4.07952 28.3636 5.21318 31.0227 8.45779 35.9273C9.1875 37.0303 12.8361 39 17.8398 39C22.8435 39 25.8665 37.0303 26.5962 35.9273C30.134 30.5795 30.9745 28.3636 32.5382 24.8182V18.4364C35.6656 3.30909 24.5824 0.237441 17.1811 0.00107729Z\" fill=\"#734B3B\"/>
                    <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M12.1529 26L12.153 26.0002V26H23.8471V26.0001L23.8472 26L36.0001 42.56H24.3058H11.6943H0L12.1529 26ZM13.2468 32.992L16.4989 28.9967V28.9966H16.499H19.6282H19.6283V28.9966L22.8804 32.992H19.7511H16.376H13.2468Z\" fill=\"#541B1B\"/>
                    <path d=\"M18 33.36L30.1243 45.09H5.87561L18 33.36Z\" fill=\"#541B1B\"/>
                    <path d=\"M18.3999 49L13.5502 41.41L23.2497 41.41L18.3999 49Z\" fill=\"#541B1B\"/>
                </svg>";
    }
}
