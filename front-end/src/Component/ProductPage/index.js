import * as React from 'react';
import axios from 'axios';
import { Container, Grid, Box, Pagination, Select, Checkbox, MenuItem, InputLabel, FormControlLabel } from '@mui/material';
import ProductCard from './ProductCard';
import { useParams } from 'react-router';


function ProductPage() {
    const [filter, setFilter] = React.useState(0);
    const query = new URLSearchParams(window.location.search);
    const category = query.get('category');
    const fil = query.get('fil');
    const search = query.get('search');
    const [page, setPage] = React.useState(1);
    const [sort, setSort] = React.useState(1);
    const [title, setTitle] = React.useState('Sản Phẩm');
    const [perPage, setPerPage] = React.useState(10);
    const [productList, setProductList] = React.useState(['1']);
    const [product, setProduct] = React.useState(['1'])
    async function loadTable() {
        if (category) {
            await axios.get(`/api/product?category=${category}&sort=${sort}&filter=${filter}`).then(product => {
                setProductList(product.data.data);
                setProduct(product.data.data.slice((page * perPage) - perPage, ((page * perPage) - perPage) + perPage));
                axios.get(`/api/category/one/${category}`).then(product => { setTitle(product.data.data.name) });
            })
        } else {
            await axios.get(`/api/product?sort=${sort}&sort=${sort}&filter=${filter}&search=${search}`).then(product => {
                setProductList(product.data.data);
                setProduct(product.data.data.slice((page * perPage) - perPage, ((page * perPage) - perPage) + perPage));
            })
            if (search){
                setTitle(`Từ khóa: "${search}"`)
            }
        }


    }
    const maxpage = Math.ceil(productList.length / perPage)
    React.useEffect(() => {
        setFilter(fil)
    },[])
    React.useEffect(() => {
        loadTable();

    }, [perPage, sort, filter]);

    function parseImagePath(string) {
        if (string) {
            let img = JSON.parse(string)
            return img.path
        }
    }

    const handleChange = (event) => {
        if (filter != event.target.value){
            setFilter(event.target.value);
        }
        else {
            setFilter(0);
        }
      };
    
    return (

        <Container >
            <h1>{title}</h1>
            <Grid container spacing={2}>
                <Grid item xs={2}>
                    <InputLabel id="demo-multiple-name-label">Sắp xếp</InputLabel>
                    <Select
                        variant="standard"
                        value={sort}
                        onChange={(e) => { setSort(e.target.value) }}
                        label="Sắp xếp"
                    >

                        <MenuItem
                            key={1}
                            value={1}
                        >
                            Giá tăng dần
                        </MenuItem>
                        <MenuItem
                            key={2}
                            value={2}
                        >
                            Giá giảm dần
                        </MenuItem>
                        <MenuItem
                            key={3}
                            value={3}
                        >
                            Đánh giá cao nhất
                        </MenuItem>
                        <MenuItem
                            key={4}
                            value={4}
                        >
                            Mới nhất
                        </MenuItem>
                    </Select>
                </Grid>
                <Grid item  xs>
                    <InputLabel id="demo-multiple-name-label">Hiển thị</InputLabel>
                    <Select
                        variant="standard"
                        value={perPage}
                        onChange={(e) => { setPerPage(e.target.value) }}
                    >

                        <MenuItem
                            key={5}
                            value={5}
                        >
                            5
                        </MenuItem>
                        <MenuItem
                            key={10}
                            value={10}
                        >
                            10
                        </MenuItem>
                        <MenuItem
                            key={20}
                            value={20}
                        >
                            20
                        </MenuItem>


                    </Select>
                </Grid>
                <Grid item xs={2}>
                    <FormControlLabel control={<Checkbox />} label="Sản phẩm nổi bật" checked={(filter==1)} onChange={handleChange} value={1}/>
                </Grid>
                <Grid item xs={2}>
                    <FormControlLabel control={<Checkbox />} label="Đang giảm giá" checked={(filter==2)} onChange={handleChange} value={2} />
                </Grid>
            </Grid>
            <Grid container spacing={2} sx={{ mt: 4 }}>

                {product.map((item, index) => (
                    <Grid item xs={3}>
                        <ProductCard key={index} name={item.name} price={item.price} description={item.description} image={parseImagePath(item.thumbnail)} id={item.id} score={item.score} hot={item.hot} discount={item.productDiscounts}></ProductCard>
                    </Grid>
                ))}

            </Grid>
            <Pagination sx={{ mt: 2 }} count={maxpage} onChange={(e, v) => { setProduct(productList.slice((v * perPage) - perPage, ((v * perPage) - perPage) + perPage)) }} />
        </Container>

    );
}
export default ProductPage