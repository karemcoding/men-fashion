import { Box } from '@mui/material';
import React from 'react';
import Carousel from 'react-material-ui-carousel'
export default function Banner(props) {
    var items = [
        '/banner/Banner-Coolmate-Active-opt-1.jpeg',
        '/banner/Desktop-Banner-CLEARANCE-SALE-_100.jpeg',
        '/banner/Desktop-Banner-Hero-Tat.jpeg',
        '/banner/Desktop-Hero-banner-PRMVN1.jpeg'
    ]
    return (

        <Carousel
            animation="slide"
            sx={{heigh: '100%'}}
        >
            {items.map((item) =>
                <Box
                    component="img"
                    src={item}
                    sx={{
                        width: '100%'
                    }}
                    >
                </Box>)}
        </Carousel>
    )
}