import { Container, IconButton, Paper, Grid,FormControl, Modal, Alert, Box, Divider,InputLabel,MenuItem,Select, CssBaseline, Typography, TextField, Button, Card, CardMedia, CardContent, CardActions, Link } from '@mui/material'
import axios from 'axios'
import React from 'react';

import CancelRoundedIcon from '@mui/icons-material/CancelRounded';
import AddCircleRoundedIcon from '@mui/icons-material/AddCircleRounded';
import DoDisturbOnRoundedIcon from '@mui/icons-material/DoDisturbOnRounded';


const BasicModal = React.forwardRef((props, ref) => {

    const [open, setOpen] = React.useState(false);
    React.useImperativeHandle(ref, () => ({
        handleOpen() {
            setOpen(true);

        }
    }));

    return (
        <div>
            <Modal
                open={open}
                aria-labelledby="modal-modal-title"
                aria-describedby="modal-modal-description"
            >
                <Alert variant="filled" severity="success">
                    Đặt hàng thành công! Đang chuyển hướng đến trang sản phẩm...
                </Alert>
            </Modal>
        </div>
    );
})

function CartItem(props) {
    const handleAdd = async () => {
        const data = {
            product_id: props.id,
            quantity: parseInt(props.quantity) + 1,
        }
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.post("/api/cart/add", data, headers).then(function (response) {
            console.log(response)
            props.handleAdd();
        });
    }
    const handleSub = async () => {
        const data = {
            product_id: props.id,
            quantity: parseInt(props.quantity) - 1,
        }
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.post("/api/cart/add", data, headers).then(function (response) {
            console.log(response)
            props.handleAdd();
        });
    }
    const handleClr = async () => {
        const data = {
            product_id: props.id,
        }
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.post("/api/cart/remove", data, headers).then(function (response) {
            console.log(response)
            props.handleAdd();
        });
    }

    return (
        <Card sx={{ display: 'flex', my: 1, width: '100%', }} variant="outlined">
            <CardMedia
                component="img"
                sx={{ width: 200 }}
                image={`${axios.defaults.baseURL}/${JSON.parse(props.thumbnail).path}`}
            />
            <Box sx={{ display: 'flex', flexDirection: 'column' }}>
                <CardContent sx={{ flex: '1 0 auto' }}>
                    <Link href={`/product/${props.id}`} underline='none' color='black'>
                        <Typography gutterBottom variant="h6" component="div" sx={{
                            display: '-webkit-box',
                            overflow: 'hidden',
                            WebkitBoxOrient: 'vertical',
                            WebkitLineClamp: 3,
                            width: 1
                        }} onHover>
                            {props.name}
                        </Typography></Link>
                    <Typography variant="body1  " color="text.primary" component="div">
                        Size: {props.size}
                    </Typography>
                    {(typeof props.discount !== 'undefined' && typeof props.discount[0] !== 'undefined')
                        ? <>
                            <Typography display="inline" variant="body1" color="text.primary">
                                Giá:  {(parseInt(props.discount[0].discount_price) * props.quantity).toLocaleString()}đ
                            </Typography>
                            <Typography display="inline" variant="body2" color="text.secondary" sx={{ ml: 2 }}>
                                <del>{parseInt(props.price).toLocaleString()}đ</del></Typography>
                        </>
                        : <Typography variant="body1  " color="text.secondary" component="div">
                            Giá: {parseInt(props.price).toLocaleString()} Đ
                        </Typography>}

                </CardContent>
                <CardActions>
                    <IconButton onClick={handleAdd} color='primary'><AddCircleRoundedIcon /></IconButton>
                    <Typography variant="body1  " color="text.secondary" sx={{ mx: 2 }}>
                        Số lượng: {props.quantity}
                    </Typography>
                    <IconButton onClick={handleSub}><DoDisturbOnRoundedIcon /></IconButton>
                    <IconButton onClick={handleClr} color='error'><CancelRoundedIcon /></IconButton>

                </CardActions>

            </Box>

        </Card>)
}




export default function CartPage(props) {
    const [errorText, setErrorText] = React.useState('1');

    const [profile, setProfile] = React.useState('');
    const [order, setOrder] = React.useState(['']);
    const [total, setTotal] = React.useState('');
    const getProfile = async () => {
        if (localStorage.token) {
            const token = localStorage.getItem("token");
            const headers = { headers: { Authorization: `Bearer ${token}` } };
            await axios.get("/api/profile/get", headers).then(function (response) {
                setProfile(response.data.data);
                console.log(profile)
            });

        }
    };
    React.useEffect(() => {
        getProfile();
    }, []);



    const [product, setProduct] = React.useState('1');
    const getProduct = async () => {
        if (localStorage.token) {
            const token = localStorage.getItem("token");
            const headers = { headers: { Authorization: `Bearer ${token}` } };
            await axios.get("/api/cart/get", headers).then(function (response) {
                setProduct(response.data.data);
                console.log(response)
            });
            await axios.post("/api/cart/cal", '', headers).then(function (response) {
                setTotal(response.data.data.amount);
            });
        }
    };
    React.useEffect(() => {
        getProduct();

    }, []);
    const delay = ms => new Promise(res => setTimeout(res, ms));
    const direct = async () => {
        await delay(2000);
        window.location.href = '/product';
    };

    const handleOrder = (e) => {
        e.preventDefault();
        const formData = new FormData(e.currentTarget);
        const data = {
            receiver: formData.get('name'),
            receiver_tel: formData.get('phone'),
            address: formData.get('address'),
            remark: formData.get('note'),
            method: formData.get('method')
        }
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        axios.post(`/api/order/add`, data, headers).then(response => {
            if (response.data.data.status !== 65) {
                childRef.current.handleOpen();
                direct();
            }
        })
    }
    // const handleAdd = React.useCallback(() => {
    //     getProduct();
    // }, [])
    const childRef = React.useRef();
    return (
        <Box sx={{ flexGrow: 1 }}>
            <Grid container spacing={2}>
                <Grid item xs={6}>
                    <Container component="main">
                        <CssBaseline />
                        <Box
                            sx={{
                                marginTop: 3,
                                display: 'flex',
                                flexDirection: 'column',
                                alignItems: 'center',
                            }}
                        >
                            <Typography component="h1" variant="h5">
                                Thông tin đặt hàng
                            </Typography>
                            {profile !== '' ?
                                <Box component="form" onSubmit={handleOrder} noValidate sx={{ mt: 1 }}>
                                    <TextField
                                        defaultValue={profile.name}
                                        error={(!errorText.name) ? false : true}
                                        helperText={errorText.name}
                                        margin="normal"
                                        required
                                        fullWidth
                                        id="name"
                                        label="Tên"
                                        name="name"
                                        autoComplete="name"

                                    />
                                    <TextField
                                        error={(!errorText.phone) ? false : true}
                                        helperText={errorText.phone}
                                        defaultValue={profile.phone}
                                        margin="normal"
                                        required
                                        fullWidth
                                        id="phone"
                                        label="Số điện thoại"
                                        name="phone"
                                        autoComplete="phone"
                                    />
                                    <TextField
                                        error={(!errorText.address) ? false : true}
                                        helperText={errorText.address}
                                        defaultValue={profile.address}
                                        margin="normal"
                                        required
                                        fullWidth
                                        id="address"
                                        label="Địa chỉ"
                                        name="address"
                                        autoComplete="address"
                                    />
                                    <FormControl fullWidth sx={{mt:2}}>
                                    <InputLabel id="demo-simple-select-label">Phương thức thanh toán</InputLabel>
                                    <Select
                                    labelId="demo-simple-select-label"
                                        label="Phương thức thanh toán"
                                        id="method"
                                        name="method"
                                    >
                                        <MenuItem value={20}>Chuyển khoản</MenuItem>
                                        <MenuItem value={30}>Tiền mặt</MenuItem>
                                    </Select>
                                    </FormControl>
                                    <TextField
                                        error={(!errorText.note) ? false : true}
                                        helperText={errorText.note}
                                        margin="normal"
                                        fullWidth
                                        id="note"
                                        label="Ghi chú"
                                        name="note"
                                        autoComplete="note"
                                    />
                                    <Button
                                        type="submit"
                                        fullWidth
                                        variant="contained"
                                        sx={{ mt: 3, mb: 2 }}
                                    >
                                        Đặt hàng
                                    </Button>
                                </Box> : <></>}
                        </Box>
                    </Container>
                </Grid>
                <Grid item xs={6} sx={{ borderLeft: 1, borderColor: 'grey.300' }}>
                    <Box
                        sx={{
                            marginTop: 3,
                            display: 'flex',
                            flexDirection: 'column',
                            alignItems: 'center',
                        }}
                    >
                        <Typography component="h1" variant="h5">
                            Giỏ hàng
                        </Typography>
                        {product === '1'
                            ? <Container>Giỏ hàng chưa có sản phẩm nào</Container>
                            : <Container>
                                {product.map((item, index) => (
                                    <CartItem key={index} name={item.name} size={item.size} thumbnail={item.thumbnail} price={item.total} quantity={item.quantity} id={item.id} handleAdd={getProduct} discount={item.productDiscounts}></CartItem>
                                ))}
                            </Container>}

                    </Box>
                    <Divider flexItem sx={{ m: 2 }} />
                    <Typography component="h1" variant="h5">
                        Tổng cộng: {parseInt(total).toLocaleString()} VNĐ
                    </Typography>
                </Grid>
            </Grid>
            <BasicModal ref={childRef} />
        </Box>
    )

}