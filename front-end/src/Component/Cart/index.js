import * as React from 'react';
import { IconButton, Badge, Menu, MenuItem, Box, Typography, Container, Link, Button, Card, CardContent, CardMedia } from '@mui/material/'
import LocalMallIcon from '@mui/icons-material/LocalMall';
import axios from 'axios';
import ClearIcon from '@mui/icons-material/Clear';
function CartItem(props) {
    const handleClr = async () => {
        const data = {
            product_id: props.id,
        }
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.post("/api/cart/remove", data, headers).then(function (response) {
            console.log(response)
        });
    }
    return (
        <Card sx={{ display: 'flex', width: '100%', my: 1 }} variant="outlined">
            <CardMedia
                component="img"
                sx={{ width: 100 }}
                image={`${axios.defaults.baseURL}/${JSON.parse(props.thumbnail).path}`}
            />
            <Box sx={{ display: 'flex', flexDirection: 'column' }}>
                <CardContent sx={{ flex: '1 0 auto' }}>
                <Link href={`/product/${props.id}`} underline='none' color='black'>
                    <Typography gutterBottom variant="body1" component="div" sx={{
                        display: '-webkit-box',
                        overflow: 'hidden',
                        WebkitBoxOrient: 'vertical',
                        WebkitLineClamp: 2,
                        width: 200,
                    }} onHover>
                        {props.name}
                    </Typography></Link>
                    {(typeof props.discount !== 'undefined' && typeof props.discount[0] !== 'undefined')
                        ? <>
                            <Typography display="inline" variant="body1" color="text.primary">
                            Giá:  {(parseInt(props.discount[0].discount_price)*props.quantity).toLocaleString()}đ
                            </Typography>
                            <Typography display="inline" variant="body2" color="text.secondary" sx={{ ml: 2 }}>
                                <del>{parseInt(props.price).toLocaleString()}đ</del></Typography>
                        </>
                        : <Typography variant="body1  " color="text.secondary" component="div">
                            Giá: {parseInt(props.price).toLocaleString()} Đ
                        </Typography>}
                    <Typography variant="body1  " color="text.primary" component="div">
                        Size: {props.size}
                    </Typography>
                    <Typography variant="body1  " color="text.primary">
                        Số lượng: {props.quantity}
                    </Typography>

                </CardContent>
            </Box>
            <Box sx={{width:1}}></Box>
            <Button onClick={handleClr}><ClearIcon></ClearIcon></Button>
        </Card>)
}
function Cart(props) {
    const [product, setProduct] = React.useState('1');
    const getProduct = async () => {
        if (localStorage.token) {
            const token = localStorage.getItem("token");
            const headers = { headers: { Authorization: `Bearer ${token}` } };
            await axios.get("/api/cart/get", headers).then(function (response) {
                setProduct(response.data.data);
            });
            await axios.post("/api/cart/cal",'', headers)
        }
    };
    const [anchorEl, setAnchorEl] = React.useState(null);
    React.useEffect(() => {
        getProduct();
    }, [anchorEl]);

    
    const handleCartOpen = (event) => {
        setAnchorEl(event.currentTarget);
    };
    const isCartOpen = Boolean(anchorEl);
    const handleCartClose = () => {
        setAnchorEl(null);
    };
    const renderCart = (
        <Menu
            anchorEl={anchorEl}
            keepMounted
            open={isCartOpen}
            onClose={handleCartClose}>
            {(product === '1' || product.length==0) 
                ? <Container>Giỏ hàng chưa có sản phẩm nào</Container>
                : <Container>
                    {product.map((item, index) => (
                        <CartItem key={index} name={item.name} thumbnail={item.thumbnail} price={item.total} quantity={item.quantity} id={item.id} size={item.size} discount={item.productDiscounts}></CartItem>
                    ))}
                    <Button href="/cart" variant="contained" fullWidth>Thanh toán </Button>
                </Container>}
            {/* {product.map((item, index) => (
                <MenuItem key={index}>
                    <CartItem name={item.name} thumbnail={item.thumbnail}></CartItem>
                </MenuItem>))} */}
        </Menu>
    );

    return (
        <>
       
            <IconButton size="large"
                onClick={handleCartOpen}
                edge="end"
                aria-haspopup="true"
                color="inherit">
                 <Badge badgeContent={props.total} color="error">
                    <LocalMallIcon />
                    </Badge>
            </IconButton>
            {renderCart}
        </>
    );
}

export default Cart;