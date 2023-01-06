import * as React from 'react';
import Card from '@mui/material/Card';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import Typography from '@mui/material/Typography';
import { Badge, CardActionArea, Rating } from '@mui/material';
import axios from 'axios';
import LocalMallIcon from '@mui/icons-material/LocalMall';



export default function ProductCard(props) {
  return (
    <Badge badgeContent={'Nổi bật'} color="error" invisible={(props.hot != 10)}>
      <Card


      >
        <CardActionArea
        >
          <CardMedia
            onClick={() => { window.location.href = '/product/' + props.id }}
            component="img"
            height="300"
            image={`${axios.defaults.baseURL}/${props.image}`}
            alt="green iguana"

          />
        </CardActionArea>
        <CardContent>
          <Typography gutterBottom variant="h6" component="div" sx={{
            display: '-webkit-box',
            overflow: 'hidden',
            WebkitBoxOrient: 'vertical',
            WebkitLineClamp: 2,
          }} onHover>
            {props.name}

          </Typography>

          {(typeof props.discount !== 'undefined' && typeof props.discount[0] !== 'undefined')
            ?
            <>
              <Typography display="inline" variant="body1" color="text.primary">
                {parseInt(props.discount[0].discount_price).toLocaleString()}đ
              </Typography>
              <Typography display="inline" variant="body2" color="text.secondary" sx={{ ml: 2 }}>
                <del>{parseInt(props.price).toLocaleString()}đ</del></Typography>
            </>
            : <Typography variant="body1" color="text.primary">
              {parseInt(props.price).toLocaleString()}đ
            </Typography>
          }
          <Typography>
            {props.score ?
              <Rating value={props.score} readOnly precision={0.2} /> : <Rating value="0" disabled />}</Typography>

        </CardContent>
      </Card >
    </Badge>
  );
}



