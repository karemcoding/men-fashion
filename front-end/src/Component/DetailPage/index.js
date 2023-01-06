import { Grid, Box, Typography, Snackbar, Alert, Rating, TextField, Select, MenuItem, OutlinedInput, Button, Container, InputLabel, FormControl, Paper, Avatar,Pagination, } from '@mui/material';
import React from 'react';
import Carousel from 'react-material-ui-carousel';
import axios from 'axios';
import { useParams } from 'react-router';
import ShoppingCartIcon from '@mui/icons-material/ShoppingCart';




function Feedback(props) {
    let time = new Date(props.time * 1000).toLocaleDateString("vn");
    return (
        <Paper style={{ padding: "20px 20px", marginTop: 10 }}>
            <Grid container wrap="nowrap" spacing={2}>
                <Grid item>
                    <Avatar alt="Remy Sharp" src={props.imgLink} />
                </Grid>
                <Grid justifyContent="left" item xs zeroMinWidth>
                    <h4 style={{ margin: 0, textAlign: "left" }}>{props.name}</h4>


                    <p style={{ textAlign: "left" }}>
                        {props.feedback}
                    </p>
                    <p style={{ textAlign: "left", color: "gray" }}>
                        {time}
                    </p>
                </Grid>
                <Rating name="size-large" defaultValue={props.score} readOnly />
            </Grid>
        </Paper>)
}

function FeedbackForm(props) {
    
    const handleSubmit = (event) => {
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        const data = new FormData(event.currentTarget);
        axios.post(`/api/feedback/add`, {
            product_id: props.product,
            score: data.get('score'),
            feedback: data.get('feedback'),
        }, headers).then(response => { console.log(response) })
    };
    const [value, setValue] = React.useState(2);
    return (
        <Paper style={{ padding: "20px 20px", marginTop: 10 }}>
            <Grid container wrap="nowrap" spacing={2}>
                <Grid item>
                    <Avatar alt="Remy Sharp" src={props.imgLink} />
                </Grid>



                <Grid component="form" onSubmit={handleSubmit} justifyContent="left" item xs zeroMinWidth>
                    <Rating name="score" value={value} size="large" onChange={(event, newValue) => {
                        setValue(newValue);
                    }} />
                    <TextField
                        placeholder="Đánh giá"
                        id="outlined-multiline-static"
                        fullWidth
                        multiline
                        rows={4}
                        name="feedback"
                    />
                    <Button type="submit" variant="contained" size="large" sx={{ mt: 1 }}>Đăng</Button>
                </Grid>

            </Grid>
        </Paper>)
}

export default function DetailPage(props) {
    const [page, setPage] = React.useState(1);
    const [sort, setSort] = React.useState(1);
    const [perPage, setPerPage] = React.useState(5);
    const [feedbackList, setFeedbackList] = React.useState(['1'])
    const [related, setRelated] = React.useState()
    
    const handleAdd = async () => {
        props.func()
        var id = getObjKey(related,size);
        const data = {
            product_id: id,
            quantity: quantity,
            size: size
        }
        const token = localStorage.getItem("token");
        const headers = { headers: { Authorization: `Bearer ${token}` } };
        await axios.post("/api/cart/add", data, headers).then(function (response) {
        
            setOpen(true);
        });
    }

    const { id } = useParams();

    const [sizes, setSizes] = React.useState([])


    const handleChange = (event) => {
        const {
            target: { value },
        } = event;
        setSize(value);
    };
    const [quantity, setQuantity] = React.useState(1)
    const handleChangeQuantity = (event) => {
        setQuantity(event.target.value);
    };
    const [product, setProduct] = React.useState(['1']);
    const [feedback, setFeedback] = React.useState([]);
    const maxpage = Math.ceil(feedback.length / perPage)
    const getProduct = async () => {
        await axios.get(`/api/product/one/${id}`).then(function (response) {
            setProduct(response.data.data.product);
            setFeedback(response.data.data.feedbacks);
            
            var a = []
            for (var property in response.data.data.related) {
                a.push(`${response.data.data.related[property]}`);
            }
            setSizes(a)
            setRelated(response.data.data.related);
            setFeedbackList(response.data.data.feedbacks.slice((page * perPage) - perPage, ((page * perPage) - perPage) + perPage));
        })
        
    };
    
    const [size, setSize] = React.useState('');
    React.useEffect(() => {
        getProduct();

        setSize(product.size);
    }, [product.size]);

    function parseImagePath(string) {
        var a = [];
        if (string) {
            let img = JSON.parse(string)

            img.forEach(function (obj) {
                a.push(obj.path);
            })
        }
        return a
    }

    const [open, setOpen] = React.useState(false);

    const handleClose = (event, reason) => {
        if (reason === 'clickaway') {
            return;
        }

        setOpen(false);
    };

    function getObjKey(obj, value) {
        return Object.keys(obj).find(key => obj[key] === value);
      }
    return (
        <Container>
            <Snackbar open={open} autoHideDuration={6000} onClose={handleClose} anchorOrigin={{ vertical: 'top', horizontal: 'center' }} >
                <Alert onClose={handleClose} severity="success" sx={{ width: '100%' }}>
                    Thêm vào giỏ hàng thành công!
                </Alert>
            </Snackbar>
            <Grid container spacing={2}>
                <Grid item xs={6}>
                    <Box >
                        <Carousel
                            height={500}
                            animation="slide"
                            sx={{
                                width: '100%',
                                backgroundPosition: 'center center',
                                backgroundRepeat: 'no-repeat',
                            }
                            }
                        >
                            {parseImagePath(product.gallery).map((item) =>
                                <Box
                                    component="img"
                                    sx={{
                                        width: '100%',
                                    }
                                    }
                                    src={`${axios.defaults.baseURL}/${item}`}>
                                </Box>
                            )
                            }


                        </Carousel>
                    </Box>
                </Grid>
                <Grid item xs={6}>
                    <Box sx={{ mx: 2 }}>
                        <Box>
                            <Typography variant="h5">{product.name}</Typography>

                            <Box
                                sx={{
                                    display: 'flex',
                                    alignItems: 'center',
                                }}
                            >
                                {product.score ?
                                    <Rating value={product.score} readOnly precision={0.2} label={product.score} />


                                    : <Rating value="0" disabled />}

                                <p style={{ textAlign: "left", color: "gray", marginLeft: "5px" }}>
                                    {feedback.length} lượt đánh giá
                                </p>
                            </Box>
                            <Typography variant="h6" color="gray">Giá: {parseInt(product.price).toLocaleString()} Đ</Typography>
                        </Box>

                        <Box>

                            <TextField
                                sx={{ mt: 3 }}
                                id="outlined-number"
                                label="Số Lượng"
                                type="number"
                                InputLabelProps={{
                                    shrink: true,
                                }}
                                InputProps={{ inputProps: { min: 1, max: 99 } }}
                                value={quantity}
                                onChange={handleChangeQuantity}
                            />
                            <FormControl sx={{ mt: 3, ml: 3 }}>
                                <InputLabel id="demo-multiple-name-label">Size</InputLabel>
                                <Select
                                    value={size}
                                    displayEmpty
                                    input={<OutlinedInput label="Size" />}
                                    onChange={handleChange}
                                >

                                    {sizes.map((name) => (
                                        <MenuItem
                                            key={name}
                                            value={name}
                                        >
                                            {name}
                                        </MenuItem>

                                    ))}

                                </Select>
                            </FormControl>
                        </Box>

                        <Button variant="contained" endIcon={<ShoppingCartIcon />} sx={{ mt: 3 }} onClick={handleAdd}>
                            Thêm vào giỏ hàng
                        </Button>
                    </Box>
                </Grid>
                <Grid item xs={12}>
                    <h2>Chi tiết sản phẩm</h2>
                    <Typography variant="body" color="gray" dangerouslySetInnerHTML={{ __html: product.description }}></Typography>

                </Grid>
                <Grid item xs={12}>
                    <h2>Đánh giá</h2>
                    <FeedbackForm product={product.id} />
                    {feedbackList.map((item) => (<Feedback name={item.user} feedback={item.feedback} score={item.score} time={item.created_at} />))}
                </Grid>
                <Pagination sx={{ mt: 2 }} count={maxpage} onChange={(e, v) => { setFeedbackList(feedback.slice((v * perPage) - perPage, ((v * perPage) - perPage) + perPage)) }} />
            </Grid>


        </Container>
    )

}