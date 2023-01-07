import React from 'react';
import ProductCard from '../ProductPage/ProductCard';
import axios from 'axios';
import { Container, Link, Grid } from '@mui/material'

import { useTranslation } from 'react-i18next';

export default function DiscountProduct() {
    const { t, i18n } = useTranslation();
    const [productList, setProductList] = React.useState(['1']);
    async function loadTable() {
        await axios.get(`/api/product?limit=5&filter=2`).then(product => {
            setProductList(product.data.data);
            console.log(productList)

        })
    }
    React.useEffect(() => {
        loadTable();

    }, []);


    function parseImagePath(string) {
        if (string) {
            let img = JSON.parse(string)
            return img.path
        }
    }

    return (

        <Grid sx={{ my: 6 }}>
        <Grid container justifyContent="flex-end">
            <h2>{t('sales')}</h2>
            <Grid item  xs></Grid>
            <Link href="product?fil=2"><h3>{t('more')}</h3></Link>
            </Grid>
            <Grid container spacing={{ xs: 2, md: 3 }} columns={{ xs: 4, sm: 8, md: 12 }}>

                    {productList.map((item, index) => (
                        <Grid item xs={2} sm={4} md={4}>
                            <ProductCard key={index} name={item.name} price={item.price} description={item.description} image={parseImagePath(item.thumbnail)} id={item.id} score={item.score} hot={item.hot} discount={item.productDiscounts}></ProductCard>
                        </Grid>
                    ))}

                </Grid>
        </Grid>)
}