import * as React from 'react';
import { Container, Grid, Box, Typography, TextField, Button, Link, Table, TableRow, TableCell, TableContainer, TableHead, TableBody, Paper } from '@mui/material'
import axios from 'axios';


function ProfilePage() {

    const [profile, setProfile] = React.useState('');
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


    const handleSubmit = () => {
        console.log('hello')
    }

    return (
        <Box sx={{ flexGrow: 1, mt: 5 }}>
            <Grid container spacing={2}>
                <Grid item xs={4}>
                    <Box
                        sx={{
                            display: 'flex',
                            flexDirection: 'column',
                            mx: 2
                        }}
                    >
                        <Typography component="h1" variant="h5">
                            Thông tin tài khoản
                        </Typography>
                        {profile !== '' ?
                            <Box component="form" noValidate onSubmit={handleSubmit} sx={{ mt: 3 }}>
                                <Grid container spacing={2}>
                                    <Grid item xs={12}>
                                        <TextField
                                            defaultValue={profile.name}
                                            autoComplete="name"
                                            name="name"
                                            required
                                            fullWidth
                                            id="name"
                                            label="Tên"
                                        />
                                    </Grid>
                                    <Grid item xs={12}>
                                        <Grid>
                                            <TextField
                                                defaultValue={profile.email}
                                                autoComplete="email"
                                                name="email"
                                                required
                                                fullWidth
                                                id="email"
                                                label="Email"
                                            />
                                        </Grid>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <Grid>
                                            <TextField
                                                defaultValue={profile.phone}
                                                autoComplete="phone"
                                                name="phone"
                                                required
                                                fullWidth
                                                id="phone"
                                                label="Số điện thoại"
                                            />
                                        </Grid>
                                    </Grid>
                                    <Grid item xs={12}>
                                        <Grid>
                                            <TextField
                                                defaultValue={profile.address}
                                                autoComplete="address"
                                                name="address"
                                                required
                                                fullWidth
                                                id="address"
                                                label="Địa chỉ"
                                            />
                                        </Grid>
                                    </Grid>
                                </Grid>
                                <Button
                                    type="submit"
                                    fullWidth
                                    variant="contained"
                                    sx={{ mt: 3, mb: 2 }}
                                >
                                    Cập nhật thông tin
                                </Button>
                                <Button
                                    fullWidth
                                    variant="outlined"
                                >
                                    Đổi mật khẩu
                                </Button>
                                <Grid container justifyContent="flex-end">
                                    <Grid item>
                                    </Grid>
                                </Grid>
                            </Box> : <></>}
                    </Box>
                </Grid>
                <Grid item xs={6} sx={{ borderLeft: 1, borderColor: 'grey.300' }} >
                    <Box
                        sx={{
                            display: 'flex',
                            flexDirection: 'column',
                            alignItems: 'center',
                        }}
                    >
                        <Typography sx={{ mb: 4 }} component="h1" variant="h5">
                            Lịch sử đơn hàng
                        </Typography >
                        <BasicTable ></BasicTable>
                    </Box>

                </Grid>
            </Grid>

        </Box>
    );
}


function BasicTable() {
    const [data, setData] = React.useState('');
    const getData = async () => {
        if (localStorage.token) {
            const token = localStorage.getItem("token");
            const headers = { headers: { Authorization: `Bearer ${token}` } };
            await axios.get("/api/order/get", headers).then(function (response) {
                setData(response.data.data);
                console.log(response.data.data)
            });

        }
    };
    function setStatus(data) {
        switch (data) {
            case "10":
                return "ĐÃ HOÀN THÀNH";
            case "20":
                return "ĐANG VẬN CHUYỂN"
            case "-10":
                return "ĐÃ HỦY"
            case "-20":
                return "HOÀN TRẢ"
            default:
                return "ĐÃ TẠO";
        }
    }
    function setPaymentStatus(data) {
        switch (data) {
            case "-10":
                return "CHƯA THANH TOÁN"
            default:
                return "ĐÃ THANH TOÁN";
        }
    }
    function setPaymentStatus(data) {
        switch (data) {
            case "30":
                return "TIỀN MẶT"
            default:
                return "CHUYỂN KHOẢN";
        }
    }




    React.useEffect(() => {
        getData();
    }, [data.number]);
    return (
        <TableContainer component={Paper}>
            {data !== '' ?
                <Table aria-label="simple table">
                    <TableHead>
                        <TableRow>
                            <TableCell align="right">Mã đơn hàng</TableCell>
                            <TableCell align="right">Địa chỉ nhận</TableCell>
                            <TableCell align="right">Thành tiền</TableCell>
                            <TableCell align="right">Trạng thái</TableCell>
                            <TableCell align="right">Thanh toán</TableCell>
                            <TableCell align="right">Phương thức thanh toán</TableCell>
                            <TableCell align="right">Ngày tạo</TableCell>
                        </TableRow>
                    </TableHead>
                    <TableBody>
                        {data.map((row) => (
                            <TableRow
                                key={row.name}
                                sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                            >
                                <TableCell align="right">{row.number}</TableCell>
                                <TableCell align="right">{row.address}</TableCell>
                                <TableCell align="right">{parseInt(row.total).toLocaleString()} VNĐ</TableCell>
                                <TableCell align="right">{setStatus(row.status)}</TableCell>
                                <TableCell align="right">{setPaymentStatus(row.payment_status)}</TableCell>
                                <TableCell align="right">{row.payment_method}</TableCell>
                                <TableCell align="right">{new Date(row.created_at * 1000).toLocaleDateString("vn")}</TableCell>
                            </TableRow>
                        ))}
                    </TableBody>
                </Table> : <></>}
        </TableContainer>
    );
}




export default ProfilePage;