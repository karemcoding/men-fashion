import React from 'react';
import ProductCard from '../ProductPage/ProductCard';
import axios from 'axios';
import { Link, Grid } from '@mui/material'

import { useTranslation } from 'react-i18next';

export default function HotProduct() {

  const { t, i18n } = useTranslation();

    const [productList, setProductList] = React.useState(['1']);
    async function loadTable() {
        await axios.get(`/api/product?limit=5&filter=1`).then(product => {
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

        <Grid>
            <Grid container justifyContent="flex-end">

                <h2>{t('featured')}</h2>
                <Grid item xs></Grid>
                <Link href="product?fil=1"><h3>{t('more')}</h3></Link></Grid>
            <Grid container spacing={2}>

                {productList.map((item, index) => (
                    <Grid item xs={3}>
                        <ProductCard key={index} name={item.name} price={item.price} description={item.description} image={parseImagePath(item.thumbnail)} id={item.id} score={item.score} hot={item.hot} discount={item.productDiscounts}></ProductCard>
                    </Grid>
                ))}

            </Grid>
        </Grid>)
}