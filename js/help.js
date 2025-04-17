function scrollToFooter() {
    const footer = document.querySelector('footer'); // Get the footer element
    // Option 1: scrollIntoView (smooth scrolling)
    footer.scrollIntoView({ behavior: 'smooth' });

    // Option 2: scrollTo (less smooth, more direct)
    // window.scrollTo(0, footer.offsetTop); // Offset from top of document
  }