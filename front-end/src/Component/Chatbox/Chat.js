import * as React from 'react';
import { Button, Fab, Paper, Popper, Input, IconButton, Box, TextField, Container, Grid, Link } from '@mui/material';
import ChatIcon from '@mui/icons-material/Chat';
import { MessageBox, MessageList } from "react-chat-elements";
import "react-chat-elements/dist/main.css"
import CloseIcon from '@mui/icons-material/Close';
import axios from "axios";
import ProductCard from '../ProductPage/ProductCard';
export default function Chat() {
  const [productList, setProductList] = React.useState(['1']);
  const [anchorEl, setAnchorEl] = React.useState(null);
  const [chat, setChat] = React.useState('');
  const [link, setLink] = React.useState('');
  const [data, setData] = React.useState([{
    title: "Kà Rem",
    titleColor: "Black",
    backgroundColor: "white",
    position: "left",
    type: "text",
    text: "Đây là Kà Rem, chatbot của cửa hàng. Bạn đang tìm gì?"
  }]);
  const handleChatOpen = (event) => {
    setAnchorEl(event.currentTarget);
    if (anchorEl !== null) {
      handleChatClose()
    }
  };
  function parseImagePath(string) {
    if (string) {
      let img = JSON.parse(string)
      return img.path
    }
  }
  const isChatOpen = Boolean(anchorEl);
  const handleChatClose = () => {
    setAnchorEl(null);
  };
  const handleChat = async () => {
    setData([...data, {
      position: "right",
      type: "text",
      text: chat
    
    }])
    console.log()
    setChat('')
    if (chat !== '') {
      await axios.post(`http://localhost:5005/webhooks/rest/webhook`, {
        sender: 'test_user',
        message: chat
      }).then(function (response) {
        var item = []
        var rep = response.data[0].text
        if (typeof (response.data[1]) != 'undefined') {
          axios.get(response.data[1].image).then(function (response) {
            item = response.data.data
            setData([...data, {
              position: "right",
              type: "text",
              text: chat
            }, {
              title: "Kà Rem",
              titleColor: "Black",
              position: "left",
              type: "text",
              text: rep,
            }, {
              title: "Kà Rem",
              titleColor: "Black",
              position: "left",
              type: "text",
              text:
                <Grid container spacing={3}>
                  {item.map((item, index) => (
                    <Grid item xs={4}>
                      <ProductCard key={index} name={item.name} price={item.price} description={item.description} image={parseImagePath(item.thumbnail)} id={item.id} score={item.score} hot={item.hot} discount={item.productDiscounts}></ProductCard>
                    </Grid>
                  ))}
                </Grid>,
            }
            ])
          });

        } else {
          setData([...data, {
            position: "right",
            type: "text",
            text: chat
          }, {
            title: "Kà Rem",
            titleColor: "Black",
            position: "left",
            type: "text",
            text: response.data[0].text,
          }])
        }
      })

      
    }
    setChat('')
  }
  const handleKeyDown = (event) => {
    if (event.key === 'Enter') {
      handleChat();
    }
  }
  const renderChat = (
    <Popper
      sx={{ zIndex: 5, boxShadow: 3, backgroundColor: "white" }}
      placement='left-end'
      anchorEl={anchorEl}
      keepMounted
      open={isChatOpen}
      onClose={handleChatClose}>

      <Container>
        <Box sx={{ mt: 2 }}>
          <IconButton onClick={handleChatClose}><CloseIcon></CloseIcon></IconButton></Box>
        <Box
          sx={{ width: 500, height: 500, overflow: "auto" }} >
          <MessageList
            messageBoxStyles={{ backgroundColor: "rgb(0, 132, 255)", color: "white" }}
            notchStyle={{ fill: "rgb(0, 132, 255)" }}
            dataSource={data}
            className='message-list'
          />
        </Box>

        <Grid container spacing={2} sx={{ mt: 2, mb: 3 }}>
          <Grid item xs={9}>

            <Input
              value={chat}
              margin="normal"
              fullWidth={true}
              required
              placeholder="Aa"
              id="chat"
              name="chat"
              autoFocus
              onChange={(e) => setChat(e.target.value)}
              onKeyDown={handleKeyDown}
            />
          </Grid>
          <Grid item xs={3}>
            <Button variant="contained" onClick={handleChat}>Send</Button>
          </Grid>
        </Grid>
      </Container>
    </Popper>);
  return (<>
    <Fab sx={{

      position: 'fixed',
      bottom: 20,
      right: 20,
    }}
      onClick={handleChatOpen}>
      <ChatIcon></ChatIcon>
    </Fab>
    {renderChat}
  </>)
}