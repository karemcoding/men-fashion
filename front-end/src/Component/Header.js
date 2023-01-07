import * as React from 'react';
import axios from 'axios';
import { styled, alpha } from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box'; import Select from '@mui/material/Select';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import InputBase from '@mui/material/InputBase';
import List from '@mui/material/List';
import Divider from '@mui/material/Divider';
import ListItem from '@mui/material/ListItem';
import ListItemButton from '@mui/material/ListItemButton';
import ListItemIcon from '@mui/material/ListItemIcon';
import ListItemText from '@mui/material/ListItemText';
import MenuIcon from '@mui/icons-material/Menu';
import InboxIcon from '@mui/icons-material/MoveToInbox';
import Drawer from '@mui/material/Drawer';
import FormControl from '@mui/material/FormControl';
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

import { useTranslation } from 'react-i18next';

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
  const [state, setState] = React.useState(false);
  const toggleDrawer = (open) => (event) => {
    if (event.type === 'keydown' && (event.key === 'Tab' || event.key === 'Shift')) {
      return;
    }


    setState(open);
  };

  const changeLanguage = (event) => {
    i18n.changeLanguage(event.target.value);
  }

  const { t, i18n } = useTranslation();
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
    window.location.href = `/product?search=${data.get('search')}`
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
      ? [<MenuItem key="thongtintaikhoan" onClick={() => { window.location.href = "/profile" }}>{t('profile')}</MenuItem>,
      <MenuItem key="dangxuat" onClick={handleSignOut}>{t('logout')}</MenuItem>]
      : [<MenuItem onClick={() => { window.location.href = "/signin" }}>{t('login')}</MenuItem>,
      <MenuItem onClick={() => { window.location.href = "/signup" }}>{t('signup')}</MenuItem>
      ]}
    </Menu>
  );

  const list = (
    <Box
      sx={{ width: 250 }}
      role="presentation"
      onClick={toggleDrawer(false)}
      onKeyDown={toggleDrawer(false)}
    >

      <List>
        <ListItem>
          <Typography >{(profile.name) ? profile.name : profile.email}</Typography>
        </ListItem>
        <ListItem disablePadding>

          <ListItemButton
            href="/#"
            variant="h6"
            noWrap
            sx={{ display: 'block' }}
          >
            MEN-FASHION
          </ListItemButton>
        </ListItem>
          <ListItemButton
            href="/product"
            noWrap
          >
            {t('products')}
          </ListItemButton>
          <ListItemButton
              variant="h6"
              noWrap
              href="/about"
            >
              {t('about')}
            </ListItemButton>
      </List>
    </Box>
  );
  return (
    <Box sx={{ flexGrow: 1, mb: 2 }}>
      <AppBar position="static" color='inherit'>
        <Toolbar color="gray">
          <div>
            <IconButton
              color="inherit"
              aria-label="open drawer"
              sx={{ flexGrow: 1, display: { xs: 'flex', md: 'none' } }}
              onClick={toggleDrawer(true)}
              edge="start"
            >
              <MenuIcon />
            </IconButton>
            <Drawer
              anchor={'left'}
              open={state}
              onClose={toggleDrawer(false)}
            >
              {list}
            </Drawer>
          </div>
          <Button
            href="/#"
            variant="h6"
            noWrap
            sx={{ display: 'block' }}
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
                    {t('products')}
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
              {t('about')}
            </Button>
          </Box>






          <Box sx={{ flexGrow: 1 }} />






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
          <Box sx={{ display: { xs: 'none', md: 'flex' }, justifyContent: "center", alignItems: "center" }}>


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
            <Cart total={props.total} />
          </Box>
          <FormControl variant="standard" sx={{ width: 120, ml: 4 }}>
            <Select
              labelId="demo-simple-select-label"
              id="demo-simple-select"
              onChange={changeLanguage}
              value={i18n.language}
            >
              <MenuItem value={'vi'}>Tiếng Việt</MenuItem>
              <MenuItem value={'en'}>English</MenuItem>
              <MenuItem value={'jp'}>日本</MenuItem>
            </Select>
          </FormControl>
        </Toolbar>
      </AppBar>
      {renderMenu}

    </Box>
  );
}