import * as React from 'react';
import axios from 'axios';
import { styled, alpha } from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import InputBase from '@mui/material/InputBase';
import Badge from '@mui/material/Badge';
import MenuItem from '@mui/material/MenuItem';
import Menu from '@mui/material/Menu';
import SearchIcon from '@mui/icons-material/Search';
import AccountCircle from '@mui/icons-material/AccountCircle';
import MailIcon from '@mui/icons-material/Mail';
import NotificationsIcon from '@mui/icons-material/Notifications';
import MoreIcon from '@mui/icons-material/MoreVert';
import Button from '@mui/material/Button';
import PopupState, { bindHover, bindMenu } from 'material-ui-popup-state';
import HoverMenu from 'material-ui-popup-state/HoverMenu'
import Cart from './Cart'

const Search = styled('div')(({ theme }) => ({
  position: 'relative',
  borderRadius: theme.shape.borderRadius,
  backgroundColor: alpha(theme.palette.common.white, 0.15),
  '&:hover': {
    backgroundColor: alpha(theme.palette.common.white, 0.25),
  },
  marginRight: theme.spacing(2),
  marginLeft: 0,
  width: '100%',
  [theme.breakpoints.up('sm')]: {
    marginLeft: theme.spacing(3),
    width: 'auto',
  },
}));

const SearchIconWrapper = styled('div')(({ theme }) => ({
  padding: theme.spacing(0, 2),
  height: '100%',
  position: 'absolute',
  pointerEvents: 'none',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({ theme }) => ({
  color: 'inherit',
  '& .MuiInputBase-input': {
    padding: theme.spacing(1, 1, 1, 0),
    // vertical padding + font size from searchIcon
    paddingLeft: `calc(1em + ${theme.spacing(4)})`,
    transition: theme.transitions.create('width'),
    width: '100%',
    [theme.breakpoints.up('md')]: {
      width: '20ch',
    },
  },
}));

export default function Header(props) {
  const [category, setCategory] = React.useState(['1']);
  const [profile, setProfile] = React.useState(['']);
  // const [cart, setCart] = React.useState(['']);
  const getCategory = async () => {
    await axios.get("/api/category").then(function (response) {
      setCategory(response.data.data);
    });
  };
  const getProfile = async () => {
    if (localStorage.token) {
      const token = localStorage.getItem("token");
      const headers = { headers: { Authorization: `Bearer ${token}` } };
      await axios.get("/api/profile/get", headers).then(function (response) {
        setProfile(response.data.data);
      });
    }
  };
  React.useEffect(() => {
    getCategory();
    getProfile();
  }, []);

  const [anchorEl, setAnchorEl] = React.useState(null);
  const [mobileMoreAnchorEl, setMobileMoreAnchorEl] = React.useState(null);

  const isMenuOpen = Boolean(anchorEl);
  const isMobileMenuOpen = Boolean(mobileMoreAnchorEl);

  const handleProfileMenuOpen = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleMobileMenuClose = () => {
    setMobileMoreAnchorEl(null);
  };

  const handleMenuClose = () => {
    setAnchorEl(null);
    handleMobileMenuClose();
  };

  const handleSignOut = () => {
    localStorage.clear()
    window.location.href = "/"
  };

const handleSearch = (e) => {
  e.preventDefault();
  const data = new FormData(e.currentTarget)
   window.location.href=`/product?search=${data.get('search')}`
}

  const handleMobileMenuOpen = (event) => {
    setMobileMoreAnchorEl(event.currentTarget);
  };

  const menuId = 'primary-search-account-menu';
  const renderMenu = (
    <Menu
      anchorEl={anchorEl}
      anchorOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      id={menuId}
      keepMounted
      transformOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      open={isMenuOpen}
      onClose={handleMenuClose}
    >{(localStorage.token)
      ? [<MenuItem key="thongtintaikhoan" onClick={() => { window.location.href = "/profile" }}>Thông tin tài khoản</MenuItem>,
      <MenuItem key="dangxuat" onClick={handleSignOut}>Đăng xuất</MenuItem>]
      : [<MenuItem onClick={() => { window.location.href = "/signin" }}>Đăng nhập</MenuItem>,
      <MenuItem onClick={() => { window.location.href = "/signup" }}>Tạo tài khoản</MenuItem>
      ]}
    </Menu>
  );

  const mobileMenuId = 'primary-search-account-menu-mobile';
  const renderMobileMenu = (
    <Menu
      anchorEl={mobileMoreAnchorEl}
      anchorOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      id={mobileMenuId}
      keepMounted
      transformOrigin={{
        vertical: 'top',
        horizontal: 'right',
      }}
      open={isMobileMenuOpen}
      onClose={handleMobileMenuClose}
    >
      <MenuItem>
        <IconButton size="large" aria-label="show 4 new mails" color="inherit">
          <Badge badgeContent={4} color="error">
            <MailIcon />
          </Badge>
        </IconButton>
        <p>Messages</p>
      </MenuItem>
      <MenuItem>
        <IconButton
          size="large"
          aria-label="show 17 new notifications"
          color="inherit"
        >
          <Badge badgeContent={17} color="error">
            <NotificationsIcon />
          </Badge>
        </IconButton>
        <p>Notifications</p>
      </MenuItem>
      {profile.email}
      <MenuItem onClick={handleProfileMenuOpen}>
        <IconButton
          size="large"
          aria-label="account of current user"
          aria-controls="primary-search-account-menu"
          aria-haspopup="true"
          color="inherit"
        >
          <AccountCircle />
        </IconButton>
        <p>Profile</p>
      </MenuItem>
    </Menu>
  );

  return (
    <Box sx={{ flexGrow: 1, mb: 2 }}>
      <AppBar position="static" color='inherit'>
        <Toolbar color="gray">

          <Button
            href="/#"
            variant="h6"
            noWrap
            sx={{ display: { xs: 'none', sm: 'block' } }}
          >
            MEN-FASHION</Button>
          <Box sx={{ flexGrow: 1, display: { xs: 'none', md: 'flex' } }}>
            <PopupState popupId="demo-popup-menu">
              {(popupState) => (
                <React.Fragment>
                  <Button
                    {...bindHover(popupState)}
                    href="/product"
                    variant="h6"
                    sx={{
                      m: 2, display: 'block'
                    }}
                    noWrap
                  >
                    Sản phẩm
                  </Button>
                  <HoverMenu {...bindMenu(popupState)}>
                    {category.map((item, index) => (
                      <MenuItem
                        component="a"
                        href={`/product?category=${item.id}`}
                        key={index}
                      >
                        {item.depth === '1'
                          ?
                          <Typography variant='body1'>
                            {item.name}</Typography>
                          : <Typography variant='body2' color='gray' >
                            &nbsp;&nbsp;{item.name}</Typography>
                        }
                      </MenuItem>))}
                  </HoverMenu>
                </React.Fragment>
              )}
            </PopupState>
            <Button
              variant="h6"
              noWrap
              href="/about"
              sx={{ m: 2, display: 'block' }}
            >
              Về chúng tôi
            </Button>
          </Box>






          <Box sx={{ flexGrow: 1 }}/>
          <Box component="form" onSubmit={handleSearch}>
          <Search >
            <SearchIconWrapper>
              <SearchIcon />
            </SearchIconWrapper>
            <StyledInputBase
              name="search"
              placeholder="Search…"
              inputProps={{ 'aria-label': 'search' }}
            />
          </Search>
          </Box>
          <Box sx={{ display: { xs: 'none', md: 'flex', justifyContent: "center", alignItems: "center" } }}>


            <Typography >{(profile.name) ? profile.name : profile.email}</Typography>
            <IconButton
              size="large"
              edge="end"
              aria-label="account of current user"
              aria-controls={menuId}
              aria-haspopup="true"
              onClick={handleProfileMenuOpen}
              color="inherit"
            >

              <AccountCircle />
            </IconButton>
            <Cart total={props.total}/>
          </Box>
          <Box sx={{ display: { xs: 'flex', md: 'none' } }}>
            <IconButton
              size="large"
              aria-label="show more"
              aria-controls={mobileMenuId}
              aria-haspopup="true"
              onClick={handleMobileMenuOpen}
              color="inherit"
            >
              <MoreIcon />
            </IconButton>

          </Box>
        </Toolbar>
      </AppBar>
      {renderMobileMenu}
      {renderMenu}
    </Box>
  );
}