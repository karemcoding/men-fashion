import * as React from 'react';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import CssBaseline from '@mui/material/CssBaseline';
import TextField from '@mui/material/TextField';
import Link from '@mui/material/Link';
import Grid from '@mui/material/Grid';
import Box from '@mui/material/Box';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import Typography from '@mui/material/Typography';
import Container from '@mui/material/Container';
import { createTheme, ThemeProvider } from '@mui/material/styles';
import axios from 'axios'


const theme = createTheme();

export default function SignUp() {
    if (localStorage.token) {
        window.location.href = "/#";
    }
    const [errorText, setErrorText] = React.useState({
        name: '',
        phone: '',
        email: '',
        password: '',
        confirm_password: '',
    });
    const handleSubmit = (event) => {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        axios.post(`/api/site/signup`, {
            name: data.get('name'),
            phone: data.get('phoneNumber'),
            email: data.get('email'),
            password: data.get('password'),
            confirm_password: data.get('confirmPassword'),
        }).then(function (response) {
            if (response.data.data.status === 200) {
                window.location.href = "/signin";
            } else {
                setErrorText(response.data.data.errors)
            }

        });
    };

    return (
        <ThemeProvider theme={theme}>
            <Container component="main" maxWidth="xs">
                <CssBaseline />
                <Box
                    sx={{
                        marginTop: 8,
                        display: 'flex',
                        flexDirection: 'column',
                        alignItems: 'center',
                    }}
                >
                    <Avatar sx={{ m: 1 }}>
                        <LockOutlinedIcon />
                    </Avatar>
                    <Typography component="h1" variant="h5">
                        Đăng ký
                    </Typography>
                    <Box component="form" noValidate onSubmit={handleSubmit} sx={{ mt: 3 }}>
                        <Grid container spacing={2}>
                            <Grid item xs={12}>
                                <TextField
                                    error={(!errorText.email) ? false : true}
                                    helperText={errorText.email}
                                    autoComplete="email"
                                    name="email"
                                    required
                                    fullWidth
                                    id="email"
                                    label="Email"
                                    autoFocus
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <TextField
                                    error={(!errorText.name) ? false : true}
                                    helperText={errorText.name}
                                    required
                                    fullWidth
                                    id="name"
                                    label="Tên"
                                    name="name"
                                    autoComplete="name"
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <TextField
                                    error={(!errorText.phone) ? false : true}
                                    helperText={errorText.phone}
                                    required
                                    fullWidth
                                    id="phoneNumber"
                                    label="Số điện thoại"
                                    name="phoneNumber"
                                    autoComplete="phoneNumber"
                                    type="tel"
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <TextField
                                    error={(!errorText.password) ? false : true}
                                    helperText={errorText.password}
                                    required
                                    fullWidth
                                    name="password"
                                    label="Mật khẩu"
                                    type="password"
                                    id="password"
                                    autoComplete="new-password"
                                />
                            </Grid>
                            <Grid item xs={12}>
                                <TextField
                                    error={(!errorText.confirm_password) ? false : true}
                                    helperText={errorText.confirm_password}
                                    required
                                    fullWidth
                                    name="confirmPassword"
                                    label="Xác nhận mật khẩu"
                                    type="password"
                                    id="confirmPassword"
                                    autoComplete="new-password"
                                />
                            </Grid>
                        </Grid>
                        <Button
                            type="submit"
                            fullWidth
                            variant="contained"
                            sx={{ mt: 3, mb: 2 }}
                        >
                            Đăng ký
                        </Button>
                        <Grid container justifyContent="flex-end">
                            <Grid item>
                                <Link href="/signin" variant="body2">
                                    Đã có tài khoản? Đăng nhập
                                </Link>
                            </Grid>
                        </Grid>
                    </Box>
                </Box>
            </Container>
        </ThemeProvider>
    );
}