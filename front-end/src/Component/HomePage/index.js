
import { Container } from '@mui/material';
import React from 'react';
import ProductCard from '../ProductPage/ProductCard';
import Banner from './Banner';
import HotProduct from './HotProduct';
import DiscountProduct from './DiscountProduct';
function HomePage() {
    return (
        <Container>
        <Banner/>
        <HotProduct></HotProduct>
        <DiscountProduct/>
        </Container>

    );
}
export default HomePage